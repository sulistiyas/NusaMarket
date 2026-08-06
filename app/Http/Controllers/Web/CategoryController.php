<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Models\Category;
use App\Services\CategoryService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct(private CategoryService $service) {}

    public function index(Request $request)
    {
        $breadcrumbs = ['Kategori Produk' => route('categories.index')];
        return view('pages.categories.index', compact('breadcrumbs'));
    }

    public function store(StoreCategoryRequest $request)
    {
        $this->service->create($request->validated());

        return redirect()->route('categories.index')
            ->with('success', 'Kategori baru berhasil ditambahkan!');
    }

    public function update(StoreCategoryRequest $request, Category $category)
    {
        $this->service->update($category, $request->validated());

        return redirect()->route('categories.index')
            ->with('success', 'Kategori berhasil diperbarui!');
    }

    public function destroy(Category $category)
    {
        $this->service->delete($category);

        return redirect()->route('categories.index')
            ->with('success', 'Kategori berhasil dihapus!');
    }
}
