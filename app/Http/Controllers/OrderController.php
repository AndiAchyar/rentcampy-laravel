<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\RentalOrder;
use App\Support\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = $request->user()->orders()->with('items')->latest()->get();

        return view('orders.index', compact('orders'));
    }

    /** Checkout: membuat permohonan sewa dari produk terpilih di keranjang. */
    public function store(Request $request)
    {
        // Pengunjung: simpan isian checkout, minta login, lalu kembali ke keranjang.
        if (! $request->user()) {
            $request->session()->put('checkout_draft', $request->only('delivery_address', 'start_date', 'end_date', 'selected'));
            $request->session()->put('url.intended', route('cart'));

            return redirect()->route('login')
                ->with('info', 'Keranjangmu tetap tersimpan. Masuk terlebih dahulu untuk mengirim permohonan sewa.');
        }

        // Sinkronkan jumlah dari form ke keranjang sebelum validasi.
        foreach ((array) $request->input('quantities', []) as $id => $qty) {
            if (is_numeric($qty)) {
                Cart::set((int) $id, (int) $qty);
            }
        }

        $data = $request->validate([
            'selected' => ['required', 'array', 'min:1', 'max:50'],
            'selected.*' => ['integer'],
            'delivery_address' => ['required', 'string', 'min:5', 'max:500'],
            'start_date' => ['required', 'date_format:Y-m-d', 'after_or_equal:today'],
            'end_date' => ['required', 'date_format:Y-m-d', 'after_or_equal:start_date'],
        ], [
            'selected.required' => 'Pilih setidaknya satu produk yang ingin disewa.',
            'selected.min' => 'Pilih setidaknya satu produk yang ingin disewa.',
            'selected.max' => 'Jumlah jenis produk terlalu banyak.',
            'delivery_address.required' => 'Alamat pengiriman wajib diisi.',
            'delivery_address.min' => 'Alamat pengiriman harus berisi 5 sampai 500 karakter.',
            'delivery_address.max' => 'Alamat pengiriman harus berisi 5 sampai 500 karakter.',
            'start_date.required' => 'Tanggal mulai sewa wajib diisi.',
            'start_date.after_or_equal' => 'Tanggal mulai tidak boleh di masa lalu.',
            'end_date.required' => 'Tanggal kembali wajib diisi.',
            'end_date.after_or_equal' => 'Tanggal kembali tidak boleh sebelum tanggal mulai.',
            '*.date_format' => 'Tanggal peminjaman tidak valid.',
        ]);

        $cart = Cart::raw();
        $quantities = [];
        foreach (array_unique($data['selected']) as $id) {
            if (isset($cart[$id])) {
                $quantities[(int) $id] = (int) $cart[$id];
            }
        }
        if (! $quantities) {
            return back()->withInput()->withErrors(['checkout' => 'Produk yang dipilih sudah tidak ada di keranjang.']);
        }

        $startDate = $data['start_date'];
        $endDate = $data['end_date'];
        // Tanggal mulai dan kembali sama-sama dihitung sebagai hari sewa.
        $rentalDays = (int) Carbon::parse($startDate)->diffInDays(Carbon::parse($endDate)) + 1;
        $productIds = array_keys($quantities);
        sort($productIds);

        try {
            $order = DB::transaction(function () use ($request, $quantities, $productIds, $startDate, $endDate, $rentalDays, $data) {
                $products = Product::whereIn('id', $productIds)->orderBy('id')->lockForUpdate()->get();
                if ($products->count() !== count($productIds)) {
                    throw new CheckoutException('Salah satu produk sudah tidak tersedia.');
                }

                $reserved = DB::table('rental_order_items as oi')
                    ->join('rental_orders as o', 'o.id', '=', 'oi.order_id')
                    ->whereIn('oi.product_id', $productIds)
                    ->whereIn('o.status', RentalOrder::ACTIVE_STATUSES)
                    ->where('o.start_date', '<=', $endDate)
                    ->where('o.end_date', '>=', $startDate)
                    ->groupBy('oi.product_id')
                    ->selectRaw('oi.product_id, SUM(oi.quantity)::int AS quantity')
                    ->pluck('quantity', 'product_id');

                foreach ($products as $product) {
                    $available = $product->stock - (int) ($reserved[$product->id] ?? 0);
                    if ($quantities[$product->id] > $available) {
                        throw new CheckoutException(sprintf(
                            'Stok %s tidak cukup untuk tanggal tersebut. Tersedia %d unit.',
                            $product->name,
                            max($available, 0)
                        ));
                    }
                }

                $total = $products->sum(fn ($p) => $p->price * $quantities[$p->id] * $rentalDays);

                $order = RentalOrder::create([
                    'user_id' => $request->user()->id,
                    'delivery_address' => trim($data['delivery_address']),
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'rental_days' => $rentalDays,
                    'total_amount' => $total,
                ]);

                foreach ($products as $product) {
                    $order->items()->create([
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'category' => $product->category,
                        'unit_price' => $product->price,
                        'quantity' => $quantities[$product->id],
                    ]);
                }

                return $order;
            });
        } catch (CheckoutException $e) {
            return back()->withInput()->withErrors(['checkout' => $e->getMessage()]);
        }

        Cart::remove($productIds);

        return redirect()->route('orders.index')->with('success', [
            'title' => 'Permohonan terkirim',
            'text' => "Pesanan #{$order->id} tersimpan. Admin akan meninjau permohonanmu.",
        ]);
    }

    public function cancel(Request $request, RentalOrder $order)
    {
        $updated = RentalOrder::where('id', $order->id)
            ->where('user_id', $request->user()->id)
            ->where('status', 'pending')
            ->update(['status' => 'cancelled', 'updated_at' => now()]);

        if (! $updated) {
            return back()->with('error', 'Pesanan tidak ditemukan atau sudah diproses admin.');
        }

        return back()->with('success', 'Pesanan dibatalkan.');
    }
}
