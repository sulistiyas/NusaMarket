@extends('layouts.app')

@section('title', 'Keranjang Belanja — NusaMarket')

@section('content')
<h1 class="text-2xl font-bold mb-6">Keranjang Belanja</h1>

@if($cartItems->isEmpty())
    <div class="cart-empty-container">
        {{-- 1. Hero Card Empty State --}}
        <div class="cart-empty-hero-card">
            <div class="dashed-circle-icon">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <h2 class="cart-empty-title">Keranjang belanja kamu masih kosong</h2>
            <p class="cart-empty-subtext">Yuk, jelajahi berbagai macam produk unggulan dan produk lokal pilihan menarik di NusaMarket lalu isi keranjangmu!</p>
            <a href="{{ route('marketplace.index') }}" class="btn btn-primary btn-empty-cta">
                <i class="fas fa-store"></i> Mulai Belanja
            </a>
        </div>

        {{-- 2. Section Kategori Populer --}}
        @if(isset($categories) && $categories->isNotEmpty())
            <div class="cart-categories-section">
                <div class="cart-section-header">
                    <h3 class="cart-section-title">Kategori Populer</h3>
                    <p class="cart-section-subtitle">Temukan produk favoritmu berdasarkan kategori terpopuler</p>
                </div>
                <div class="cart-categories-grid">
                    @php
                        $chipStyles = [
                            ['bg' => '#eff6ff', 'color' => '#1e6fd9', 'icon' => 'fa-laptop'],
                            ['bg' => '#f0f9ff', 'color' => '#0e7490', 'icon' => 'fa-shirt'],
                            ['bg' => '#f3e8ff', 'color' => '#7e22ce', 'icon' => 'fa-utensils'],
                            ['bg' => '#fef3c7', 'color' => '#b45309', 'icon' => 'fa-couch'],
                            ['bg' => '#d1fae5', 'color' => '#047857', 'icon' => 'fa-heart-pulse'],
                            ['bg' => '#ffe4e6', 'color' => '#e11d48', 'icon' => 'fa-icons']
                        ];
                    @endphp
                    @foreach($categories as $index => $category)
                        @php
                            $style = $chipStyles[$index % count($chipStyles)];
                            $iconClass = $category->icon ?: $style['icon'];
                        @endphp
                        <a href="{{ route('marketplace.index', ['category' => $category->id]) }}" class="cart-category-chip">
                            <div class="chip-icon-box" style="background-color: {{ $style['bg'] }}; color: {{ $style['color'] }};">
                                <i class="fas {{ $iconClass }}"></i>
                            </div>
                            <span class="chip-category-name">{{ $category->name }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- 3. Section Trust Strip (3 Kolom Reassurance) --}}
        <div class="cart-trust-strip">
            <div class="trust-item">
                <div class="trust-icon-wrapper trust-icon-blue">
                    <i class="fas fa-truck-fast"></i>
                </div>
                <div class="trust-info">
                    <h4 class="trust-title">Gratis Ongkir</h4>
                    <p class="trust-desc">Nikmati subsidi pengiriman ke seluruh penjuru Nusantara.</p>
                </div>
            </div>
            <div class="trust-item">
                <div class="trust-icon-wrapper trust-icon-green">
                    <i class="fas fa-shield-halved"></i>
                </div>
                <div class="trust-info">
                    <h4 class="trust-title">Produk Lokal Terpercaya</h4>
                    <p class="trust-desc">100% produk terverifikasi langsung dari UMKM pilihan.</p>
                </div>
            </div>
            <div class="trust-item">
                <div class="trust-icon-wrapper trust-icon-teal">
                    <i class="fas fa-lock"></i>
                </div>
                <div class="trust-info">
                    <h4 class="trust-title">Pembayaran Aman</h4>
                    <p class="trust-desc">Transaksi terenkripsi aman dengan beragam opsi pembayaran.</p>
                </div>
            </div>
        </div>
    </div>
@else
    <div class="cart-layout">
        {{-- List Items --}}
        <div class="cart-table-wrapper">
            @foreach($cartItems as $item)
                <div class="cart-item-row" id="cart-item-{{ $item->id }}">
                    <img 
                        src="{{ $item->product->image_url ? asset('storage/' . $item->product->image_url) : asset('images/product-placeholder.png') }}" 
                        alt="{{ $item->product->name }}" 
                        class="cart-item-img"
                    >
                    <div class="cart-item-info">
                        <a href="{{ route('marketplace.show', $item->product->id) }}" class="cart-item-title">
                            {{ $item->product->name }}
                        </a>
                        <div class="cart-item-store">
                            <i class="fas fa-store text-muted"></i> {{ $item->product->store->name ?? 'Toko NusaMarket' }}
                        </div>
                        <div class="text-primary font-bold mt-1">
                            Rp {{ number_format($item->product->price, 0, ',', '.') }}
                        </div>
                    </div>

                    <div class="flex items-center gap-4">
                        <form action="{{ route('cart.update', $item->id) }}" method="POST" class="cart-qty-control">
                            @csrf
                            @method('PUT')
                            <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="{{ $item->product->stock }}" class="form-control text-center" style="width: 70px;" onchange="this.form.submit()">
                        </form>

                        <form action="{{ route('cart.destroy', $item->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-ghost text-danger btn-sm" onclick="return confirm('Hapus item ini dari keranjang?')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Ringkasan Belanja --}}
        <div class="cart-summary-card">
            <h3 class="font-bold text-lg mb-4">Ringkasan Belanja</h3>
            
            <div class="summary-row">
                <span>Total Items</span>
                <span>{{ $cartItems->sum('quantity') }} produk</span>
            </div>

            <div class="summary-row grand-total">
                <span>Total Harga</span>
                <span class="text-primary">Rp {{ number_format($total, 0, ',', '.') }}</span>
            </div>

            <a href="{{ route('checkout.index') }}" class="btn btn-primary w-full py-3 mt-6 text-center justify-center">
                Lanjut ke Checkout <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
    </div>
@endif
@endsection
