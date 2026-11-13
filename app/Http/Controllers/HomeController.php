<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $isLoggedIn = auth()->check();

        if ($isLoggedIn) {
            // User login: katalog lengkap.
            $products = Product::orderBy('id')->get();
        } else {
            // Pengunjung: 6 produk yang paling sering disewa.
            $products = Product::query()
                ->select('products.*')
                ->selectRaw('COALESCE(SUM(CASE WHEN o.id IS NOT NULL THEN oi.quantity ELSE 0 END), 0)::int AS rental_count')
                ->leftJoin('rental_order_items as oi', 'oi.product_id', '=', 'products.id')
                ->leftJoin('rental_orders as o', function ($join) {
                    $join->on('o.id', '=', 'oi.order_id')->whereIn('o.status', ['borrowed', 'returned']);
                })
                ->groupBy('products.id')
                ->orderByDesc('rental_count')
                ->orderBy('products.id')
                ->limit(6)
                ->get();
        }

        $totalCount = $products->count();
        $keyword = trim((string) $request->query('q', ''));
        if ($keyword !== '') {
            $products = $products->filter(fn (Product $p) => Str::contains($p->name, $keyword, true)
                || Str::contains((string) $p->description, $keyword, true))->values();
        }

        return view('home', compact('products', 'keyword', 'isLoggedIn', 'totalCount'));
    }

    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    public function contact()
    {
        return view('contact');
    }
}
