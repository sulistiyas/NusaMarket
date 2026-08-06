<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(private ProductService $service) {}

    public function index(Request $request)
    {
        $breadcrumbs = ['Manajemen Produk' => route('products.index')];
        $categories = Category::where('is_active', true)->get();

        return view('pages.products.index', compact('breadcrumbs', 'categories'));
    }

    public function create()
    {
        $breadcrumbs = [
            'Manajemen Produk' => route('products.index'),
            'Tambah Produk' => route('products.create')
        ];
        $categories = Category::where('is_active', true)->get();

        return view('pages.products.create', compact('breadcrumbs', 'categories'));
    }

    public function store(StoreProductRequest $request)
    {
        $storeId = $request->user()->store->id ?? 1;
        $this->service->create($request->validated(), $storeId);

        return redirect()->route('products.index')
            ->with('success', 'Produk baru berhasil ditambahkan!');
    }

    public function show(Product $product)
    {
        $breadcrumbs = [
            'Manajemen Produk' => route('products.index'),
            $product->name => route('products.show', $product)
        ];
        $product->load(['category', 'store']);

        return view('pages.products.show', compact('breadcrumbs', 'product'));
    }

    public function edit(Product $product)
    {
        $breadcrumbs = [
            'Manajemen Produk' => route('products.index'),
            'Edit ' . $product->name => route('products.edit', $product)
        ];
        $categories = Category::where('is_active', true)->get();

        return view('pages.products.edit', compact('breadcrumbs', 'product', 'categories'));
    }

    public function update(StoreProductRequest $request, Product $product)
    {
        $this->service->update($product, $request->validated());

        return redirect()->route('products.index')
            ->with('success', 'Data produk berhasil diperbarui!');
    }

    public function destroy(Product $product)
    {
        $this->service->delete($product);

        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil dihapus!');
    }
}
