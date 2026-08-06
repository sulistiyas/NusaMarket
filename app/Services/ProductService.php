<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductService
{
    public function paginate(array $filters = []): LengthAwarePaginator
    {
        $query = Product::with(['category', 'store']);

        if (!empty($filters['search'])) {
            $query->where('name', 'ilike', '%' . $filters['search'] . '%');
        }

        if (!empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        if (!empty($filters['store_id'])) {
            $query->where('store_id', $filters['store_id']);
        }

        $sortBy = $filters['sort_by'] ?? 'id';
        $sortOrder = strtolower($filters['sort_order'] ?? 'desc') === 'asc' ? 'asc' : 'desc';
        $allowedSorts = ['name', 'price', 'stock', 'created_at', 'id'];

        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->latest();
        }

        $perPage = (int) ($filters['per_page'] ?? 10);
        return $query->paginate($perPage);
    }

    public function create(array $data, int $storeId): Product
    {
        $data['store_id'] = $storeId;
        return Product::create($data);
    }

    public function update(Product $product, array $data): Product
    {
        $product->update($data);
        return $product;
    }

    public function delete(Product $product): bool
    {
        return $product->delete();
    }
}
