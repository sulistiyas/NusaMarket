@extends('layouts.app')

@section('title', 'Daftar Pesanan — NusaMarket')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/order.css') }}">
@endpush

@section('content')
{{-- Page Loading Overlay (server-side navigation) --}}
<div id="pageLoadingOverlay" class="page-loading-overlay">
    <div class="page-loading-card">
        <div class="page-spinner"></div>
        <div class="page-loading-label">
            Memuat data
            <div class="page-loading-dots">
                <span></span><span></span><span></span>
            </div>
        </div>
    </div>
</div>

<div class="catalog-header mb-6">
    <div>
        <h1 class="text-2xl font-bold">Daftar Pesanan</h1>
        <p class="text-muted">Kelola dan pantau status transaksi pesanan Anda.</p>
    </div>

    {{-- Segmented Control Role Selector (Buyer / Seller) --}}
    <div class="segmented-control-container">
        <a 
            href="{{ route('orders.index', array_merge(request()->query(), ['role' => 'buyer'])) }}" 
            class="segmented-btn {{ $currentRole === 'buyer' ? 'segmented-btn-active' : '' }}"
        >
            <i class="ri-shopping-bag-3-line"></i> Pesanan Saya (Buyer)
        </a>
        <a 
            href="{{ route('orders.index', array_merge(request()->query(), ['role' => 'seller'])) }}" 
            class="segmented-btn {{ $currentRole === 'seller' ? 'segmented-btn-active' : '' }}"
        >
            <i class="ri-store-2-line"></i> Pesanan Masuk (Seller)
        </a>
    </div>
</div>

{{-- 1. Stat Summary Strip (4 Card) --}}
<div class="order-stats-grid">
    <div class="order-stat-card">
        <div class="stat-icon-wrapper stat-icon-total">
            <i class="ri-shopping-bag-line"></i>
        </div>
        <div class="stat-info">
            <span class="stat-value">{{ number_format($statusCounts['total'] ?? 0) }}</span>
            <span class="stat-label">Total Pesanan</span>
        </div>
    </div>

    <div class="order-stat-card">
        <div class="stat-icon-wrapper stat-icon-pending">
            <i class="ri-time-line"></i>
        </div>
        <div class="stat-info">
            <span class="stat-value">{{ number_format($statusCounts['pending'] ?? 0) }}</span>
            <span class="stat-label">Menunggu Konfirmasi</span>
        </div>
    </div>

    <div class="order-stat-card">
        <div class="stat-icon-wrapper stat-icon-processing">
            <i class="ri-loader-4-line"></i>
        </div>
        <div class="stat-info">
            <span class="stat-value">{{ number_format($statusCounts['processing'] ?? 0) }}</span>
            <span class="stat-label">Diproses</span>
        </div>
    </div>

    <div class="order-stat-card">
        <div class="stat-icon-wrapper stat-icon-completed">
            <i class="ri-checkbox-circle-line"></i>
        </div>
        <div class="stat-info">
            <span class="stat-value">{{ number_format($statusCounts['completed'] ?? 0) }}</span>
            <span class="stat-label">Selesai</span>
        </div>
    </div>
</div>

{{-- 3 & 4. Toolbar: Status Filter Pills + Search & Sort --}}
<form method="GET" action="{{ route('orders.index') }}" class="order-toolbar mb-6">
    <input type="hidden" name="role" value="{{ $currentRole }}">
    <input type="hidden" name="status" value="{{ $currentStatus }}">

    {{-- Filter Status Pills --}}
    <div class="filter-pills-group">
        @php
            $filterItems = [
                '' => ['label' => 'Semua Status', 'count' => $statusCounts['total'] ?? 0],
                'pending' => ['label' => 'Menunggu Konfirmasi', 'count' => $statusCounts['pending'] ?? 0],
                'processing' => ['label' => 'Diproses', 'count' => $statusCounts['processing'] ?? 0],
                'completed' => ['label' => 'Selesai', 'count' => $statusCounts['completed'] ?? 0],
                'cancelled' => ['label' => 'Dibatalkan', 'count' => $statusCounts['cancelled'] ?? 0],
            ];
        @endphp

        @foreach($filterItems as $key => $item)
            <a 
                href="{{ route('orders.index', array_merge(request()->except('status'), ['status' => $key])) }}" 
                class="filter-pill {{ (string)$currentStatus === (string)$key ? 'active' : '' }}"
            >
                <span>{{ $item['label'] }}</span>
                <span class="filter-pill-count">{{ $item['count'] }}</span>
            </a>
        @endforeach
    </div>

    {{-- Controls: Search Box & Sort --}}
    <div class="toolbar-controls">
        <div class="search-box">
            <i class="ri-search-line"></i>
            <input 
                type="text" 
                name="search" 
                value="{{ request('search') }}" 
                placeholder="Cari kode pesanan / toko..."
                onchange="this.form.submit()"
            >
        </div>

        <select name="sort" class="sort-select" onchange="this.form.submit()">
            <option value="latest" {{ request('sort', 'latest') === 'latest' ? 'selected' : '' }}>Terbaru</option>
            <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Terlama</option>
        </select>
    </div>
</form>

{{-- 5. Redesigned Order List Cards --}}
@if($orders->isEmpty())
    <div class="card p-8 text-center">
        <i class="ri-box-3-line text-4xl text-muted mb-3 inline-block"></i>
        <h3 class="font-bold text-lg mb-1">Belum Ada Pesanan</h3>
        <p class="text-muted mb-4">Tidak ada data pesanan yang ditemukan untuk kriteria ini.</p>
        @if(request('search') || request('status'))
            <a href="{{ route('orders.index', ['role' => $currentRole]) }}" class="btn btn-secondary inline-flex items-center gap-2">
                <i class="ri-refresh-line"></i> Reset Filter
            </a>
        @else
            <a href="{{ route('marketplace.index') }}" class="btn btn-primary inline-flex items-center gap-2">
                <i class="ri-shopping-cart-line"></i> Belanja Sekarang
            </a>
        @endif
    </div>
@else
    <div class="orders-list">
        @foreach($orders as $order)
            @php
                $statusClass = match($order->status) {
                    'pending' => 'order-card-pending',
                    'processing' => 'order-card-processing',
                    'completed' => 'order-card-completed',
                    'cancelled' => 'order-card-cancelled',
                    default => '',
                };
            @endphp

            <div class="order-card {{ $statusClass }}" x-data="{ expanded: false }">
                {{-- Left Section --}}
                <div class="order-card-main">
                    <div class="order-meta-header">
                        <span class="order-code-mono">#{{ $order->order_number }}</span>
                        
                        <div class="order-submeta">
                            <div class="order-submeta-item">
                                <i class="ri-time-line"></i>
                                <span>{{ $order->created_at->format('d M Y H:i WIB') }}</span>
                            </div>
                            <div class="order-submeta-item">
                                <i class="ri-store-2-line"></i>
                                <span>{{ $order->store->name ?? 'Toko NusaMarket' }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Items Preview with Collapsible (>2 items) --}}
                    <div class="order-items-container">
                        @foreach($order->items->take(2) as $item)
                            <div class="order-item-row">
                                <div class="order-item-left">
                                    <div class="order-item-thumb">
                                        <i class="ri-box-3-fill"></i>
                                    </div>
                                    <span class="order-item-name">{{ $item->product_name }}</span>
                                    <span class="order-item-qty">x{{ $item->quantity }}</span>
                                </div>
                                <div class="order-item-price">
                                    Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                </div>
                            </div>
                        @endforeach

                        @if($order->items->count() > 2)
                            <div x-show="expanded" class="flex flex-col gap-2">
                                @foreach($order->items->slice(2) as $item)
                                    <div class="order-item-row">
                                        <div class="order-item-left">
                                            <div class="order-item-thumb">
                                                <i class="ri-box-3-fill"></i>
                                            </div>
                                            <span class="order-item-name">{{ $item->product_name }}</span>
                                            <span class="order-item-qty">x{{ $item->quantity }}</span>
                                        </div>
                                        <div class="order-item-price">
                                            Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <button type="button" @click="expanded = !expanded" class="btn-toggle-items">
                                <span x-text="expanded ? 'Sembunyikan produk' : '+{{ $order->items->count() - 2 }} produk lainnya'"></span>
                                <i class="ri-chevron-down-line" :style="expanded ? 'transform: rotate(180deg)' : ''"></i>
                            </button>
                        @endif
                    </div>
                </div>

                {{-- Right Section --}}
                <div class="order-card-right">
                    <div class="order-status-badge">
                        @if($order->status === 'pending')
                            <span class="badge badge-warning"><span class="badge-dot pulse"></span>Menunggu Konfirmasi</span>
                        @elseif($order->status === 'processing')
                            <span class="badge badge-purple"><span class="badge-dot pulse"></span>Diproses</span>
                        @elseif($order->status === 'completed')
                            <span class="badge badge-success"><span class="badge-dot"></span>Selesai</span>
                        @else
                            <span class="badge badge-danger">Dibatalkan</span>
                        @endif
                    </div>

                    <div class="order-total-block">
                        <span class="order-total-label">Total Transaksi</span>
                        <span class="order-total-value">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                    </div>

                    <a href="{{ route('orders.show', $order->id) }}" class="btn-detail">
                        Lihat Detail <i class="ri-arrow-right-line"></i>
                    </a>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-6">
        {{ $orders->links() }}
    </div>
@endif
@endsection

@push('scripts')
    <script src="{{ asset('js/page-loading.js') }}"></script>
@endpush
