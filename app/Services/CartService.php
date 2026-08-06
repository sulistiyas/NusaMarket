<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Collection;

class CartService
{
    /**
     * Get user's cart items with product details.
     */
    public function getUserCart(int $userId): Collection
    {
        return Cart::with(['product.category', 'product.store'])
            ->where('user_id', $userId)
            ->get();
    }

    /**
     * Add product to cart.
     */
    public function addToCart(int $userId, int $productId, int $quantity = 1): Cart
    {
        $product = Product::findOrFail($productId);
        
        $cartItem = Cart::where('user_id', $userId)
            ->where('product_id', $productId)
            ->first();

        if ($cartItem) {
            $cartItem->quantity += $quantity;
            $cartItem->save();
        } else {
            $cartItem = Cart::create([
                'user_id' => $userId,
                'product_id' => $productId,
                'quantity' => $quantity,
            ]);
        }

        return $cartItem->load(['product.category', 'product.store']);
    }

    /**
     * Update quantity of a cart item.
     */
    public function updateQuantity(int $userId, int $cartId, int $quantity): Cart
    {
        $cartItem = Cart::where('user_id', $userId)
            ->where('id', $cartId)
            ->firstOrFail();

        if ($quantity <= 0) {
            $cartItem->delete();
            return $cartItem;
        }

        $cartItem->quantity = $quantity;
        $cartItem->save();

        return $cartItem->load(['product.category', 'product.store']);
    }

    /**
     * Remove item from cart.
     */
    public function removeFromCart(int $userId, int $cartId): bool
    {
        return (bool) Cart::where('user_id', $userId)
            ->where('id', $cartId)
            ->delete();
    }

    /**
     * Clear all items from user's cart.
     */
    public function clearCart(int $userId): bool
    {
        return (bool) Cart::where('user_id', $userId)->delete();
    }

    /**
     * Calculate total price of user's cart.
     */
    public function calculateTotal(int $userId): float
    {
        $items = $this->getUserCart($userId);
        return $items->sum(function ($item) {
            return $item->quantity * ($item->product->price ?? 0);
        });
    }
}
