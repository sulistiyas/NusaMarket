<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\CartService;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Exception;

class CheckoutController extends Controller
{
    protected CartService $cartService;
    protected OrderService $orderService;

    public function __construct(CartService $cartService, OrderService $orderService)
    {
        $this->cartService = $cartService;
        $this->orderService = $orderService;
    }

    public function index()
    {
        $cartItems = $this->cartService->getUserCart(auth()->id());

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang Anda kosong.');
        }

        $subtotal = $this->cartService->calculateTotal(auth()->id());
        $groupedByStore = $cartItems->groupBy('product.store_id');
        $shippingFee = $groupedByStore->count() * 15000;
        $grandTotal = $subtotal + $shippingFee;

        return view('pages.checkout.index', [
            'breadcrumbs' => ['Checkout' => route('checkout.index')],
            'cartItems' => $cartItems,
            'groupedByStore' => $groupedByStore,
            'subtotal' => $subtotal,
            'shippingFee' => $shippingFee,
            'grandTotal' => $grandTotal,
        ]);
    }

    public function store(Request $request)
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
            return redirect()->route('orders.index')->with('success', 'Pesanan Anda berhasil dibuat!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
