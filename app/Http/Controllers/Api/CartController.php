<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\CartService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    use ApiResponse;

    protected CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function index()
    {
        $cartItems = $this->cartService->getUserCart(auth()->id());
        $total = $this->cartService->calculateTotal(auth()->id());

        return $this->success([
            'items' => $cartItems,
            'total_amount' => $total,
        ], 'Data keranjang berhasil diambil.');
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'nullable|integer|min:1',
        ]);

        $quantity = $request->input('quantity', 1);
        $item = $this->cartService->addToCart(auth()->id(), $request->product_id, $quantity);

        return $this->success($item, 'Produk berhasil ditambahkan ke keranjang.', 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:0',
        ]);

        $item = $this->cartService->updateQuantity(auth()->id(), $id, $request->quantity);

        return $this->success($item, 'Keranjang berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $this->cartService->removeFromCart(auth()->id(), $id);
        return $this->success(null, 'Item berhasil dihapus dari keranjang.');
    }
}
