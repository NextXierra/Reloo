<?php

namespace App\Modules\Cart\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Cart\Services\CartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function __construct(
        protected CartService $cartService
    ) {}

    public function index(): View
    {
        $userId = auth()->id();
        $sessionId = session()->getId();

        $items = $this->cartService->getCartItems($userId, $sessionId);
        $totals = $this->cartService->calculateTotals($items);

        return view('cart::index', compact('items', 'totals'));
    }

    public function add(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'quantity' => 'nullable|integer|min:1',
            'buy_now' => 'nullable|boolean',
        ]);

        $quantity = (int) ($validated['quantity'] ?? 1);
        $userId = auth()->id();
        $sessionId = session()->getId();

        $this->cartService->addItem($validated['product_id'], $quantity, $userId, $sessionId);

        if ($request->boolean('buy_now')) {
            return redirect()->route('checkout.index');
        }
        return back();
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:0',
        ]);

        $userId = auth()->id();
        $sessionId = session()->getId();

        $this->cartService->updateQuantity($id, (int) $validated['quantity'], $userId, $sessionId);
        return back();
    }

    public function destroy(int $id): RedirectResponse
    {
        $userId = auth()->id();
        $sessionId = session()->getId();

        $this->cartService->removeItem($id, $userId, $sessionId);
        return back();
    }

    public function clear(): RedirectResponse
    {
        $userId = auth()->id();
        $sessionId = session()->getId();

        $this->cartService->clearCart($userId, $sessionId);
        return back();
    }
}
