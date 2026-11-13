<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\RentalOrder;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $byCategory = Product::selectRaw('category, COUNT(*)::int AS count')
            ->groupBy('category')
            ->orderBy('category')
            ->get();

        return view('admin.dashboard', [
            'totalProducts' => Product::count(),
            'totalUsers' => User::where('role', 'user')->count(),
            'totalOrders' => RentalOrder::count(),
            'pendingOrders' => RentalOrder::where('status', 'pending')->count(),
            'byCategory' => $byCategory,
            'maxCount' => max(1, (int) $byCategory->max('count')),
            'latestProducts' => Product::orderByDesc('id')->limit(5)->get(),
        ]);
    }
}
