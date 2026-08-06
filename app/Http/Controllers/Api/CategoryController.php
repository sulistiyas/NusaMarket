<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Services\CategoryService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    use ApiResponse;

    public function __construct(private CategoryService $service) {}

    public function index(Request $request)
    {
        $categories = $this->service->paginate($request->all());
        return $this->paginated(CategoryResource::collection($categories));
    }

    public function store(StoreCategoryRequest $request)
    {
        $category = $this->service->create($request->validated());
        return $this->success(new CategoryResource($category), 'Kategori berhasil dibuat', 201);
    }

    public function show(Category $category)
    {
        return $this->success(new CategoryResource($category));
    }

    public function update(StoreCategoryRequest $request, Category $category)
    {
        $category = $this->service->update($category, $request->validated());
        return $this->success(new CategoryResource($category), 'Kategori berhasil diperbarui');
    }

    public function destroy(Category $category)
    {
        $this->service->delete($category);
        return $this->success(null, 'Kategori berhasil dihapus');
    }
}
