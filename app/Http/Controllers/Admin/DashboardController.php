<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_revenue' => \App\Models\Order::where('status', 'delivered')->sum('total_amount'),
            'orders_count' => \App\Models\Order::count(),
            'products_count' => \App\Models\Product::count(),
            'customers_count' => \App\Models\User::count(),
            'low_stock_count' => \App\Models\Product::where('stock', '<', 10)->count(),
        ];

        $lowStockProducts = \App\Models\Product::where('stock', '<', 10)->get();
        $recentOrders = \App\Models\Order::with('items')->latest()->take(10)->get();

        return view('admin.dashboard', compact('stats', 'recentOrders', 'lowStockProducts'));
    }
}
