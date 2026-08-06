<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Services\ProductService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    use ApiResponse;

    public function __construct(private ProductService $service) {}

    public function index(Request $request)
    {
        $products = $this->service->paginate($request->all());
        return $this->paginated(ProductResource::collection($products));
    }

    public function store(StoreProductRequest $request)
    {
        $storeId = $request->user()->store->id ?? 1;
        $product = $this->service->create($request->validated(), $storeId);
        return $this->success(new ProductResource($product), 'Produk berhasil disimpan', 201);
    }

    public function show(Product $product)
    {
        $product->load(['category', 'store']);
        return $this->success(new ProductResource($product));
    }

    public function update(StoreProductRequest $request, Product $product)
    {
        $product = $this->service->update($product, $request->validated());
        return $this->success(new ProductResource($product), 'Produk berhasil diperbarui');
    }

    public function destroy(Product $product)
    {
        $this->service->delete($product);
        return $this->success(null, 'Produk berhasil dihapus');
    }
}
