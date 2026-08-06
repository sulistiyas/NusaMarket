<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\OrderService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Exception;

class OrderController extends Controller
{
    use ApiResponse;

    protected OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function index(Request $request)
    {
        $role = $request->get('role', 'buyer');
        $status = $request->get('status');

        $orders = $this->orderService->getUserOrders(auth()->id(), $role, $status);

        return $this->success($orders, 'Daftar pesanan berhasil diambil.');
    }

    public function show($id)
    {
        $order = Order::with(['items.product', 'store', 'buyer'])->findOrFail($id);
        return $this->success($order, 'Detail pesanan berhasil diambil.');
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'recipient_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'city' => 'required|string|max:100',
            'postal_code' => 'required|string|max:10',
        ]);

        $shippingAddress = [
            'recipient_name' => $request->recipient_name,
            'phone' => $request->phone,
            'address' => $request->address,
            'city' => $request->city,
            'postal_code' => $request->postal_code,
        ];

        try {
            $orders = $this->orderService->createFromCart(auth()->id(), $shippingAddress);
            return $this->success($orders, 'Pesanan berhasil dibuat.', 201);
        } catch (Exception $e) {
            return $this->error($e->getMessage(), 400);
        }
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled',
        ]);

        $order = Order::findOrFail($id);

        try {
            $updated = $this->orderService->updateStatus($order, $request->status);
            return $this->success($updated, 'Status pesanan berhasil diperbarui.');
        } catch (Exception $e) {
            return $this->error($e->getMessage(), 400);
        }
    }
}
