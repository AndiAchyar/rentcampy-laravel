<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RentalOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search', ''));
        $status = (string) $request->query('status', 'all');

        $counts = RentalOrder::selectRaw('status, COUNT(*)::int AS n')->groupBy('status')->pluck('n', 'status');

        $borrowedOrders = RentalOrder::with(['items', 'user'])
            ->where('status', 'borrowed')
            ->orderBy('end_date')
            ->get();

        $like = '%'.addcslashes($search, '%_\\').'%';
        $orders = RentalOrder::with(['items', 'user'])
            ->when(array_key_exists($status, RentalOrder::STATUS), fn ($q) => $q->where('status', $status))
            ->when($search !== '', function ($q) use ($search, $like) {
                $q->where(function ($q) use ($search, $like) {
                    $q->whereHas('user', fn ($u) => $u->where('email', 'ILIKE', $like))
                        ->orWhereHas('items', fn ($i) => $i->where('product_name', 'ILIKE', $like));
                    if (ctype_digit($search)) {
                        $q->orWhere('id', (int) $search);
                    }
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.orders', compact('orders', 'borrowedOrders', 'counts', 'search', 'status'));
    }

    public function updateStatus(Request $request, RentalOrder $order)
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(['approved', 'rejected', 'borrowed', 'returned'])],
        ], ['status.*' => 'Status pesanan tidak valid.']);

        $error = DB::transaction(function () use ($order, $data) {
            $order = RentalOrder::whereKey($order->id)->lockForUpdate()->first();
            if (! in_array($data['status'], RentalOrder::TRANSITIONS[$order->status] ?? [], true)) {
                return 'Perubahan status pesanan tidak diizinkan.';
            }
            $order->update(['status' => $data['status']]);

            return null;
        });

        if ($error) {
            return back()->with('error', $error);
        }

        return back()->with('toast', "Pesanan #{$order->id}: ".RentalOrder::STATUS[$data['status']]['label'].'.');
    }
}
