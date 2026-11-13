<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Support\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $items = Cart::items();
        // Isian checkout yang disimpan sebelum pengunjung diminta login.
        $draft = $request->session()->pull('checkout_draft', []);

        return view('cart', [
            'items' => $items,
            'draft' => $draft,
            'today' => now()->toDateString(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'quantity' => ['nullable', 'integer', 'min:1', 'max:'.Cart::MAX_QUANTITY],
        ]);
        $product = Product::findOrFail($data['product_id']);
        if ($product->stock < 1) {
            return back()->with('error', "Stok {$product->name} sedang habis.");
        }

        Cart::add($product->id, $data['quantity'] ?? 1);

        return back()->with('toast', "{$product->name} ditambahkan ke keranjang.");
    }

    public function update(Request $request, int $productId)
    {
        $data = $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:'.Cart::MAX_QUANTITY],
        ]);
        Cart::set($productId, $data['quantity']);

        if ($request->expectsJson()) {
            return response()->json(['count' => Cart::count()]);
        }

        return back();
    }

    public function destroy(int $productId)
    {
        Cart::remove($productId);

        return redirect()->route('cart')->with('toast', 'Produk dihapus dari keranjang.');
    }
}
