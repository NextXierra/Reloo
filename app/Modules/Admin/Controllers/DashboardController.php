<?php

namespace App\Modules\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Modules\Catalog\Models\Product;
use App\Modules\Order\Models\Order;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalSales = Order::whereIn('status', ['paid', 'processing', 'shipped', 'completed'])->sum('grand_total');
        $totalOrders = Order::count();
        $pendingOrders = Order::where('status', 'pending_payment')->count();
        $totalProducts = Product::where('is_active', true)->count();
        $totalUsers = User::count();

        $stats = [
            'total_sales' => $totalSales,
            'formatted_total_sales' => 'Rp ' . number_format($totalSales, 0, ',', '.'),
            'total_orders' => $totalOrders,
            'pending_orders' => $pendingOrders,
            'total_products' => $totalProducts,
            'total_users' => $totalUsers,
        ];

        $recentOrders = Order::with(['user', 'payment', 'shipment', 'items'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin::dashboard', compact('stats', 'recentOrders'));
    }
}
