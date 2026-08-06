<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\CartService;
use Illuminate\Http\Request;

use App\Models\Category;

class CartController extends Controller
{
    protected CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function index()
    {
        $cartItems = $this->cartService->getUserCart(auth()->id());
        $total = $this->cartService->calculateTotal(auth()->id());
        $categories = Category::where('is_active', true)->take(6)->get();

        return view('pages.cart.index', [
            'breadcrumbs' => ['Keranjang Belanja' => route('cart.index')],
            'cartItems' => $cartItems,
            'total' => $total,
            'categories' => $categories,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'nullable|integer|min:1',
        ]);

        $quantity = $request->input('quantity', 1);
        $this->cartService->addToCart(auth()->id(), $request->product_id, $quantity);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil ditambahkan ke keranjang.',
                'cart_count' => $this->cartService->getUserCart(auth()->id())->count()
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Produk berhasil ditambahkan ke keranjang.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:0',
        ]);

        $this->cartService->updateQuantity(auth()->id(), $id, $request->quantity);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Keranjang berhasil diperbarui.',
                'total' => $this->cartService->calculateTotal(auth()->id())
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Keranjang berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $this->cartService->removeFromCart(auth()->id(), $id);

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Item berhasil dihapus dari keranjang.'
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Item berhasil dihapus dari keranjang.');
    }
}
