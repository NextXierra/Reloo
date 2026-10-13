<?php

namespace App\Modules\Order\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Order\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $userId = Auth::id();
        $query = Order::with(['items', 'payment', 'shipment'])
            ->where('user_id', $userId);

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $orders = $query->latest()->paginate(10)->withQueryString();

        return view('order::index', compact('orders'));
    }

    public function show(string $orderNumber): View
    {
        $order = Order::with(['items.product.primaryImage', 'payment', 'shipment', 'user'])
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        // Authorization check
        if (Auth::id() !== $order->user_id && !Auth::user()->isAdmin()) {
            abort(403, 'Akses ke detail pesanan ini ditolak.');
        }

        return view('order::show', compact('order'));
    }

    public function invoice(string $orderNumber): View
    {
        $order = Order::with(['items', 'payment', 'shipment', 'user'])
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        if (Auth::id() !== $order->user_id && !Auth::user()->isAdmin()) {
            abort(403);
        }

        return view('order::invoice', compact('order'));
    }
}
