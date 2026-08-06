@extends('layouts.app')

@section('title', 'Keranjang Belanja — NusaMarket')

@section('content')
<h1 class="text-2xl font-bold mb-6">Keranjang Belanja</h1>

@if($cartItems->isEmpty())
    <div class="card p-8 text-center">
        <i class="fas fa-shopping-cart fa-3x text-muted mb-4 display-block"></i>
        <h3 class="font-bold text-lg">Keranjang Belanja Anda Kosong</h3>
        <p class="text-muted mb-6">Yuk, jelajahi produk lokal menarik di NusaMarket dan isi keranjangmu!</p>
        <a href="{{ route('marketplace.index') }}" class="btn btn-primary inline-flex items-center gap-2">
            <i class="fas fa-store"></i> Mulai Belanja
        </a>
    </div>
@else
    <div class="cart-layout">
        {{-- List Items --}}
        <div class="cart-table-wrapper">
            @foreach($cartItems as $item)
                <div class="cart-item-row" id="cart-item-{{ $item->id }}">
                    <img 
                        src="{{ $item->product->image_url ? asset('storage/' . $item->product->image_url) : 'https://via.placeholder.com/150?text=NusaMarket' }}" 
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
