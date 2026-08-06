@extends('layouts.app')

@section('title', 'Katalog Marketplace — ' . config('app.name'))

@section('content')
<div class="catalog-header">
    <div>
        <h1 class="text-2xl font-bold">Katalog Marketplace</h1>
        <p class="text-muted">Temukan produk lokal berkualitas terbaik dari UMKM Indonesia.</p>
    </div>

    <div class="catalog-search-bar">
        <form action="{{ route('marketplace.index') }}" method="GET" class="flex gap-2 w-full">
            @if(request('category_id'))
                <input type="hidden" name="category_id" value="{{ request('category_id') }}">
            @endif
            <input 
                type="text" 
                name="search" 
                class="form-control" 
                placeholder="Cari produk..." 
                value="{{ request('search') }}"
            >
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-search"></i>
            </button>
        </form>
    </div>
</div>

{{-- Filter Kategori Chip Horizontal --}}
<div class="mb-6 flex gap-2 overflow-x-auto pb-2">
    <a 
        href="{{ route('marketplace.index', array_merge(request()->except('category_id'), ['category_id' => ''])) }}" 
        class="badge {{ !request('category_id') ? 'badge-primary' : 'badge-secondary' }}"
    >
        Semua Kategori
    </a>
    @foreach($categories as $category)
        <a 
            href="{{ route('marketplace.index', array_merge(request()->except('category_id'), ['category_id' => $category->id])) }}" 
            class="badge {{ request('category_id') == $category->id ? 'badge-primary' : 'badge-secondary' }}"
        >
            {{ $category->name }}
        </a>
    @endforeach
</div>

{{-- Grid Produk --}}
@if($products->isEmpty())
    <div class="card p-8 text-center">
        <i class="fas fa-box-open fa-3x text-muted mb-4 display-block"></i>
        <h3 class="font-bold text-lg">Produk Tidak Ditemukan</h3>
        <p class="text-muted mb-4">Coba cari dengan kata kunci lain atau pilih kategori yang berbeda.</p>
        <a href="{{ route('marketplace.index') }}" class="btn btn-outline-primary inline-flex items-center gap-2">
            <i class="fas fa-sync"></i> Reset Filter
        </a>
    </div>
@else
    <div class="product-grid">
        @foreach($products as $product)
            <div class="product-card">
                <div class="product-image-wrapper">
                    <img 
                        src="{{ $product->image_url ? asset('storage/' . $product->image_url) : 'data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="400" height="300" viewBox="0 0 400 300"><rect width="400" height="300" fill="%23f0f6ff"/><text x="50%" y="50%" dominant-baseline="middle" text-anchor="middle" font-family="sans-serif" font-size="20" fill="%231e6fd9">NusaMarket</text></svg>' }}" 
                        alt="{{ $product->name }}" 
                        class="product-image"
                    >
                    <span class="product-category-badge">
                        {{ $product->category->name ?? 'Umum' }}
                    </span>
                </div>
                
                <div class="product-details">
                    <a href="{{ route('marketplace.show', $product->id) }}" class="product-title">
                        {{ $product->name }}
                    </a>
                    
                    <div class="product-store">
                        <i class="fas fa-store text-muted"></i>
                        <span>{{ $product->store->name ?? 'Toko NusaMarket' }}</span>
                    </div>

                    <div class="product-price-action">
                        <div class="product-price">
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        </div>

                        <form action="{{ route('cart.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" class="btn btn-primary btn-sm" title="Tambah ke Keranjang">
                                <i class="fas fa-cart-plus"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-6">
        {{ $products->links() }}
    </div>
@endif
@endsection
