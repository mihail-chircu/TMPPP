<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalOrders = Order::count();
        $totalRevenue = Order::where('status', '!=', 'cancelled')->sum('total');
        $totalProducts = Product::count();
        $totalUsers = User::count();

        $recentOrders = Order::with('items')
            ->latest()
            ->take(5)
            ->get();

        $lowStockProducts = Product::with('primaryImage')
            ->where('stock', '<', 5)
            ->where('is_active', true)
            ->orderBy('stock', 'asc')
            ->get();

        return view('admin.dashboard.index', compact(
            'totalOrders', 'totalRevenue', 'totalProducts', 'totalUsers',
            'recentOrders', 'lowStockProducts'
        ));
    }
}
