<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Exception;

class OrderService
{
    /**
     * Create orders from user's cart (grouped by store).
     */
    public function createFromCart(int $userId, array $shippingAddress, float $shippingFeePerStore = 15000): array
    {
        $cartItems = Cart::with('product.store')
            ->where('user_id', $userId)
            ->get();

        if ($cartItems->isEmpty()) {
            throw new Exception('Keranjang belanja Anda kosong.');
        }

        // Group cart items by store
        $groupedByStore = $cartItems->groupBy(function ($item) {
            return $item->product->store_id;
        });

        $createdOrders = [];

        DB::transaction(function () use ($userId, $groupedByStore, $shippingAddress, $shippingFeePerStore, &$createdOrders) {
            foreach ($groupedByStore as $storeId => $items) {
                $subtotal = 0;
                $orderItemsData = [];

                foreach ($items as $cartItem) {
                    $product = $cartItem->product;
                    
                    if ($product->stock < $cartItem->quantity) {
                        throw new Exception("Stok produk '{$product->name}' tidak mencukupi (Tersedia: {$product->stock}).");
                    }

                    $itemSubtotal = $product->price * $cartItem->quantity;
                    $subtotal += $itemSubtotal;

                    $orderItemsData[] = [
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'price' => $product->price,
                        'quantity' => $cartItem->quantity,
                        'subtotal' => $itemSubtotal,
                    ];

                    // Deduct product stock
                    $product->decrement('stock', $cartItem->quantity);
                }

                $totalAmount = $subtotal + $shippingFeePerStore;

                $order = Order::create([
                    'buyer_id' => $userId,
                    'store_id' => $storeId,
                    'total_amount' => $totalAmount,
                    'shipping_fee' => $shippingFeePerStore,
                    'status' => 'pending',
                    'payment_status' => 'paid',
                    'shipping_address' => $shippingAddress,
                ]);

                foreach ($orderItemsData as $itemData) {
                    $order->items()->create($itemData);
                }

                $createdOrders[] = $order;
            }

            // Clear user's cart after successful order creation
            Cart::where('user_id', $userId)->delete();
        });

        return $createdOrders;
    }

    /**
     * Get orders for a user (buyer or seller store).
     */
    public function getUserOrders(int $userId, ?string $role = 'buyer', ?string $status = null)
    {
        $query = Order::with(['items.product', 'store', 'buyer']);

        if ($role === 'seller') {
            $query->whereHas('store', function ($q) use ($userId) {
                // If the user has stores, filter by user's stores. If user is admin without specific store, show all seller orders!
                $userStoresCount = \App\Models\Store::where('user_id', $userId)->count();
                if ($userStoresCount > 0) {
                    $q->where('user_id', $userId);
                }
            });
        } else {
            // For buyer view: if user is admin and has no buyer orders, show orders or filter by buyer_id
            $userOrdersCount = Order::where('buyer_id', $userId)->count();
            if ($userOrdersCount > 0 || auth()->user()?->role !== 'admin') {
                $query->where('buyer_id', $userId);
            }
        }

        if ($status) {
            $query->where('status', $status);
        }

        return $query->latest()->get();
    }

    /**
     * Update order status.
     */
    public function updateStatus(Order $order, string $newStatus): Order
    {
        $allowedStatuses = ['pending', 'processing', 'completed', 'cancelled'];
        
        if (!in_array($newStatus, $allowedStatuses)) {
            throw new Exception('Status pesanan tidak valid.');
        }

        $order->status = $newStatus;
        $order->save();

        return $order;
    }
}
