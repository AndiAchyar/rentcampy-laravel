<?php

namespace App\Support;

use App\Models\Product;
use Illuminate\Support\Collection;

/**
 * Keranjang disimpan di session: [product_id => quantity].
 * Tetap tersimpan saat pengunjung belum login maupun setelah login.
 */
class Cart
{
    private const KEY = 'cart';

    public const MAX_QUANTITY = 99;

    public static function raw(): array
    {
        return session(self::KEY, []);
    }

    public static function add(int $productId, int $quantity = 1): void
    {
        $cart = self::raw();
        $cart[$productId] = min(self::MAX_QUANTITY, ($cart[$productId] ?? 0) + max(1, $quantity));
        session([self::KEY => $cart]);
    }

    public static function set(int $productId, int $quantity): void
    {
        $cart = self::raw();
        if (isset($cart[$productId])) {
            $cart[$productId] = min(self::MAX_QUANTITY, max(1, $quantity));
            session([self::KEY => $cart]);
        }
    }

    public static function remove(array|int $productIds): void
    {
        $cart = self::raw();
        foreach ((array) $productIds as $id) {
            unset($cart[$id]);
        }
        session([self::KEY => $cart]);
    }

    public static function count(): int
    {
        return array_sum(self::raw());
    }

    /** Produk di keranjang beserta jumlahnya. Produk yang sudah dihapus admin dibuang. */
    public static function items(): Collection
    {
        $cart = self::raw();
        if (! $cart) {
            return collect();
        }

        $products = Product::whereIn('id', array_keys($cart))->get()->keyBy('id');
        $missing = array_diff(array_keys($cart), $products->keys()->all());
        if ($missing) {
            self::remove($missing);
        }

        return collect($cart)
            ->filter(fn ($qty, $id) => $products->has($id))
            ->map(function ($qty, $id) use ($products) {
                $product = $products[$id];
                $product->quantity = $qty;

                return $product;
            })
            ->values();
    }
}
