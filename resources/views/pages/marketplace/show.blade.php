@extends('layouts.app')

@section('title', $product->name . ' — NusaMarket')

@section('content')
<div class="card p-6 mb-6">
    <div class="product-detail-layout">
        {{-- Product Image Gallery --}}
        <div class="product-detail-gallery">
            <img 
                src="{{ $product->image_url ? asset('storage/' . $product->image_url) : asset('images/product-placeholder.png') }}" 
                alt="{{ $product->name }}" 
                class="product-detail-img"
                onerror="this.src='https://via.placeholder.com/600x450?text=NusaMarket';"
            >
        </div>

        {{-- Product Info & Action Form --}}
        <div class="flex flex-col justify-between">
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <span class="badge badge-primary">{{ $product->category->name ?? 'Kategori' }}</span>
                    <span class="badge badge-secondary"><i class="fas fa-store mr-1"></i> {{ $product->store->name ?? 'Toko NusaMarket' }}</span>
                </div>

                <h1 class="text-2xl font-bold mb-3">{{ $product->name }}</h1>
                <div class="text-3xl font-bold text-primary mb-4">
                    Rp {{ number_format($product->price, 0, ',', '.') }}
                </div>

                <div class="card p-4 mb-6 bg-light border">
                    <h4 class="font-semibold mb-2">Deskripsi Produk</h4>
                    <p class="text-muted leading-relaxed">
                        {{ $product->description ?? 'Belum ada deskripsi untuk produk ini.' }}
                    </p>
                </div>
            </div>

            <div>
                <form action="{{ route('cart.store') }}" method="POST" x-data="{ qty: 1 }">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    
                    <div class="flex items-center gap-4 mb-4">
                        <label class="form-label mb-0">Jumlah:</label>
                        <div class="cart-qty-control">
                            <button type="button" class="cart-qty-btn" @click="if(qty > 1) qty--">-</button>
                            <input type="number" name="quantity" class="form-control text-center" style="width: 70px;" x-model="qty" min="1" max="{{ $product->stock }}">
                            <button type="button" class="cart-qty-btn" @click="if(qty < {{ $product->stock }}) qty++">+</button>
                        </div>
                        <span class="text-xs text-muted">Stok: {{ $product->stock }}</span>
                    </div>

                    <div class="flex gap-3">
                        <button type="submit" class="btn btn-primary flex-1 py-3 justify-center">
                            <i class="fas fa-cart-plus mr-2"></i> Tambah ke Keranjang
                        </button>
                        <a href="{{ route('marketplace.index') }}" class="btn btn-secondary">
                            Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@if($relatedProducts->isNotEmpty())
    <h3 class="text-xl font-bold mb-4">Produk Serupa</h3>
    <div class="product-grid">
        @foreach($relatedProducts as $rel)
            <div class="product-card">
                <div class="product-image-wrapper">
                    <img 
                        src="{{ $rel->image_url ? asset('storage/' . $rel->image_url) : asset('images/product-placeholder.png') }}" 
                        alt="{{ $rel->name }}" 
                        class="product-image"
                        onerror="this.src='https://via.placeholder.com/400x300?text=NusaMarket';"
                    >
                </div>
                <div class="product-details">
                    <a href="{{ route('marketplace.show', $rel->id) }}" class="product-title">{{ $rel->name }}</a>
                    <div class="product-price-action">
                        <div class="product-price">Rp {{ number_format($rel->price, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif
@endsection
