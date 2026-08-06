<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'store'])->where('is_active', true);

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('search')) {
            $query->where('name', 'ILIKE', '%' . $request->search . '%');
        }

        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'price_low':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_high':
                    $query->orderBy('price', 'desc');
                    break;
                case 'newest':
                    $query->latest();
                    break;
                default:
                    $query->latest();
                    break;
            }
        } else {
            $query->latest();
        }

        $products = $query->paginate(12)->withQueryString();
        $categories = Category::all();

        return view('pages.marketplace.index', [
            'breadcrumbs' => ['Katalog Marketplace' => route('marketplace.index')],
            'products' => $products,
            'categories' => $categories,
        ]);
    }

    public function show($id)
    {
        $product = Product::with(['category', 'store'])->findOrFail($id);
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->limit(4)
            ->get();

        return view('pages.marketplace.show', [
            'breadcrumbs' => [
                'Katalog Marketplace' => route('marketplace.index'),
                $product->name => '#'
            ],
            'product' => $product,
            'relatedProducts' => $relatedProducts,
        ]);
    }
}
