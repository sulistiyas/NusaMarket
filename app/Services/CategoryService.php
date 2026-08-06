<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Pagination\LengthAwarePaginator;

class CategoryService
{
    public function paginate(array $filters = []): LengthAwarePaginator
    {
        $query = Category::query();

        if (!empty($filters['search'])) {
            $query->where('name', 'ilike', '%' . $filters['search'] . '%');
        }

        $sortBy = $filters['sort_by'] ?? 'id';
        $sortOrder = strtolower($filters['sort_order'] ?? 'desc') === 'asc' ? 'asc' : 'desc';
        $allowedSorts = ['name', 'slug', 'is_active', 'created_at', 'id'];

        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->latest();
        }

        $perPage = (int) ($filters['per_page'] ?? 10);
        return $query->paginate($perPage);
    }

    public function create(array $data): Category
    {
        return Category::create($data);
    }

    public function update(Category $category, array $data): Category
    {
        $category->update($data);
        return $category;
    }

    public function delete(Category $category): bool
    {
        return $category->delete();
    }
}
