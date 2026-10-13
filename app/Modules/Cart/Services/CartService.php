<?php

namespace App\Modules\Cart\Services;

use App\Modules\Cart\Models\CartItem;
use App\Modules\Catalog\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\ValidationException;

class CartService
{
    /**
     * Get cart items for user or guest session
     */
    public function getCartItems(?int $userId = null, ?string $sessionId = null): Collection
    {
        $query = CartItem::with(['product.primaryImage', 'product.category']);

        if ($userId) {
            return $query->where('user_id', $userId)->get();
        }

        if ($sessionId) {
            return $query->where('session_id', $sessionId)->get();
        }

        return new Collection();
    }

    /**
     * Add product to cart with strict stock validation
     */
    public function addItem(int $productId, int $quantity = 1, ?int $userId = null, ?string $sessionId = null): CartItem
    {
        $product = Product::where('id', $productId)->where('is_active', true)->firstOrFail();

        if ($product->stock < 1) {
            throw ValidationException::withMessages([
                'stock' => "Maaf, stok produk '{$product->name}' sedang habis.",
            ]);
        }

        // Find existing item in cart
        $query = CartItem::where('product_id', $productId);
        if ($userId) {
            $query->where('user_id', $userId);
        } else {
            $query->where('session_id', $sessionId);
        }

        $existingItem = $query->first();

        if ($existingItem) {
            $newQuantity = $existingItem->quantity + $quantity;
            if ($newQuantity > $product->stock) {
                throw ValidationException::withMessages([
                    'stock' => "Jumlah melebihi stok yang tersedia (Maksimal {$product->stock} unit).",
                ]);
            }
            $existingItem->update(['quantity' => $newQuantity]);
            return $existingItem;
        }

        if ($quantity > $product->stock) {
            throw ValidationException::withMessages([
                'stock' => "Jumlah melebihi stok yang tersedia (Maksimal {$product->stock} unit).",
            ]);
        }

        return CartItem::create([
            'user_id' => $userId,
            'session_id' => $userId ? null : $sessionId,
            'product_id' => $productId,
            'quantity' => $quantity,
        ]);
    }

    /**
     * Update cart item quantity
     */
    public function updateQuantity(int $cartItemId, int $quantity, ?int $userId = null, ?string $sessionId = null): ?CartItem
    {
        $query = CartItem::with('product')->where('id', $cartItemId);
        if ($userId) {
            $query->where('user_id', $userId);
        } else {
            $query->where('session_id', $sessionId);
        }

        $item = $query->firstOrFail();

        if ($quantity <= 0) {
            $item->delete();
            return null;
        }

        if ($quantity > $item->product->stock) {
            throw ValidationException::withMessages([
                'stock' => "Stok produk hanya tersedia {$item->product->stock} unit.",
            ]);
        }

        $item->update(['quantity' => $quantity]);
        return $item;
    }

    /**
     * Remove item from cart
     */
    public function removeItem(int $cartItemId, ?int $userId = null, ?string $sessionId = null): bool
    {
        $query = CartItem::where('id', $cartItemId);
        if ($userId) {
            $query->where('user_id', $userId);
        } else {
            $query->where('session_id', $sessionId);
        }

        return (bool) $query->delete();
    }

    /**
     * Clear all items in cart
     */
    public function clearCart(?int $userId = null, ?string $sessionId = null): void
    {
        $query = CartItem::query();
        if ($userId) {
            $query->where('user_id', $userId);
        } elseif ($sessionId) {
            $query->where('session_id', $sessionId);
        } else {
            return;
        }

        $query->delete();
    }

    /**
     * Merge guest session cart into authenticated user cart
     */
    public function mergeGuestCartToUser(int $userId, string $sessionId): void
    {
        $guestItems = CartItem::where('session_id', $sessionId)->get();

        foreach ($guestItems as $guestItem) {
            $userItem = CartItem::where('user_id', $userId)
                ->where('product_id', $guestItem->product_id)
                ->first();

            if ($userItem) {
                $product = Product::find($guestItem->product_id);
                $maxStock = $product ? $product->stock : 999;
                $mergedQuantity = min($userItem->quantity + $guestItem->quantity, $maxStock);
                
                $userItem->update(['quantity' => $mergedQuantity]);
                $guestItem->delete();
            } else {
                $guestItem->update([
                    'user_id' => $userId,
                    'session_id' => null,
                ]);
            }
        }
    }

    /**
     * Calculate cart totals
     */
    public function calculateTotals(Collection $items): array
    {
        $itemsTotal = 0;
        $totalWeight = 0;
        $totalQuantity = 0;

        foreach ($items as $item) {
            if ($item->product) {
                $itemsTotal += $item->product->price * $item->quantity;
                $totalWeight += $item->product->weight_grams * $item->quantity;
                $totalQuantity += $item->quantity;
            }
        }

        return [
            'items_total' => $itemsTotal,
            'total_weight' => $totalWeight,
            'total_quantity' => $totalQuantity,
        ];
    }
}
