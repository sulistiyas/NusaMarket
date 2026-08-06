@extends('layouts.app')

@section('title', $product->name)

@section('content')
<div class="mb-4">
    <h1>Detail Produk</h1>
    <p>Rincian data dan informasi produk di NusaMarket.</p>
</div>

<div class="card" style="max-width: 900px;">
    <div class="card-header">
        <h2 class="card-title"><i class="fas fa-box-open"></i> {{ $product->name }}</h2>
        <div class="flex gap-2">
            <a href="{{ route('products.edit', $product) }}" class="btn btn-primary btn-sm">
                <i class="fas fa-edit"></i> Edit Produk
            </a>
            <a href="{{ route('products.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="grid grid-cols-1 grid-cols-sm-2 gap-4">
            <div>
                @if(!empty($product->images) && isset($product->images[0]))
                    <img src="{{ $product->images[0] }}" alt="{{ $product->name }}" style="width: 100%; max-height: 350px; border-radius: var(--radius-lg); object-fit: cover; border: 1px solid var(--border);">
                @else
                    <div style="width: 100%; height: 250px; border-radius: var(--radius-lg); background: var(--bg-light); display: flex; align-items: center; justify-content: center; color: var(--text-muted); border: 1px solid var(--border);">
                        <i class="fas fa-box fa-4x"></i>
                    </div>
                @endif
            </div>
            <div class="flex" style="flex-direction: column; gap: 12px;">
                <span class="badge badge-ocean">{{ $product->category->name ?? 'Tanpa Kategori' }}</span>
                <h2 style="font-size: var(--text-2xl); color: var(--primary-deeper);">{{ $product->name }}</h2>
                <div style="font-size: var(--text-2xl); font-weight: 800; color: var(--primary);">
                    Rp {{ number_format($product->price, 0, ',', '.') }}
                </div>
                <div class="flex items-center gap-4 mt-2">
                    <div><strong>Stok:</strong> <span class="badge badge-success">{{ $product->stock }} unit</span></div>
                    <div><strong>Berat:</strong> {{ $product->weight }} gram</div>
                </div>
                <div class="mt-2" style="border-top: 1px solid var(--border); padding-top: 12px;">
                    <strong>Disediakan Oleh:</strong> {{ $product->store->name ?? 'NusaMarket Store' }}
                </div>
                <div class="mt-2">
                    <strong>Deskripsi Produk:</strong>
                    <p style="margin-top: 6px; line-height: 1.6; color: var(--text-main);">
                        {{ $product->description ?? 'Belum ada deskripsi untuk produk ini.' }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
