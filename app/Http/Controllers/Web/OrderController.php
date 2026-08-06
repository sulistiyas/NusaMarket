<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Exception;

class OrderController extends Controller
{
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

        return view('pages.orders.index', [
            'breadcrumbs' => ['Daftar Pesanan' => route('orders.index')],
            'orders' => $orders,
            'currentRole' => $role,
            'currentStatus' => $status,
        ]);
    }

    public function show($id)
    {
        $order = Order::with(['items.product', 'store', 'buyer'])->findOrFail($id);

        return view('pages.orders.show', [
            'breadcrumbs' => [
                'Daftar Pesanan' => route('orders.index'),
                '#' . $order->order_number => '#'
            ],
            'order' => $order,
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled',
        ]);

        $order = Order::findOrFail($id);

        try {
            $this->orderService->updateStatus($order, $request->status);
            return redirect()->back()->with('success', "Status pesanan berhasil diperbarui menjadi {$request->status}.");
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
