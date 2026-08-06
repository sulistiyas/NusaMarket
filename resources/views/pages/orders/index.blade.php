@extends('layouts.app')

@section('title', 'Daftar Pesanan — NusaMarket')

@section('content')
<div class="catalog-header mb-6">
    <div>
        <h1 class="text-2xl font-bold">Daftar Pesanan</h1>
        <p class="text-muted">Kelola dan pantau status transaksi pesanan Anda.</p>
    </div>

    {{-- Role Selector (Buyer / Seller) --}}
    <div class="flex gap-2">
        <a 
            href="{{ route('orders.index', ['role' => 'buyer', 'status' => request('status')]) }}" 
            class="btn {{ $currentRole === 'buyer' ? 'btn-primary' : 'btn-secondary' }}"
        >
            <i class="fas fa-shopping-bag mr-1"></i> Pesanan Saya (Buyer)
        </a>
        <a 
            href="{{ route('orders.index', ['role' => 'seller', 'status' => request('status')]) }}" 
            class="btn {{ $currentRole === 'seller' ? 'btn-primary' : 'btn-secondary' }}"
        >
            <i class="fas fa-store mr-1"></i> Pesanan Masuk (Seller)
        </a>
    </div>
</div>

{{-- Filter Status Tabs --}}
<div class="mb-6 flex gap-2 overflow-x-auto pb-2">
    @php
        $statuses = [
            '' => 'Semua Status',
            'pending' => 'Menunggu Konfirmasi',
            'processing' => 'Diproses',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan'
        ];
    @endphp

    @foreach($statuses as $key => $label)
        <a 
            href="{{ route('orders.index', ['role' => $currentRole, 'status' => $key]) }}" 
            class="badge {{ $currentStatus === $key ? 'badge-primary' : 'badge-secondary' }}"
        >
            {{ $label }}
        </a>
    @endforeach
</div>

@if($orders->isEmpty())
    <div class="card p-8 text-center">
        <i class="fas fa-box-open fa-3x text-muted mb-4 display-block"></i>
        <h3 class="font-bold text-lg">Belum Ada Pesanan</h3>
        <p class="text-muted mb-4">Tidak ada data pesanan yang ditemukan untuk kategori ini.</p>
        <a href="{{ route('marketplace.index') }}" class="btn btn-primary inline-flex items-center gap-2">
            <i class="fas fa-store"></i> Belanja Sekarang
        </a>
    </div>
@else
    <div class="orders-list">
        @foreach($orders as $order)
            <div class="order-card">
                <div class="order-card-header">
                    <div>
                        <span class="order-number">#{{ $order->order_number }}</span>
                        <span class="order-date"><i class="far fa-clock mr-1"></i>{{ $order->created_at->format('d M Y H:i WIB') }}</span>
                    </div>

                    <div class="flex items-center gap-2">
                        @if($order->status === 'pending')
                            <span class="badge badge-warning"><span class="badge-dot pulse"></span>Menunggu Konfirmasi</span>
                        @elseif($order->status === 'processing')
                            <span class="badge badge-info"><span class="badge-dot pulse"></span>Diproses</span>
                        @elseif($order->status === 'completed')
                            <span class="badge badge-success">Selesai</span>
                        @else
                            <span class="badge badge-danger">Dibatalkan</span>
                        @endif

                        <a href="{{ route('orders.show', $order->id) }}" class="btn btn-outline-primary btn-sm btn-pill">
                            Detail <i class="fas fa-chevron-right ml-1"></i>
                        </a>
                    </div>
                </div>

                {{-- Items Preview --}}
                <div class="order-items-preview">
                    @foreach($order->items as $item)
                        <div class="order-item-row">
                            <div>
                                <span class="order-item-name">{{ $item->product_name }}</span>
                                <span class="order-item-qty">x {{ $item->quantity }}</span>
                            </div>
                            <div class="order-item-price">
                                Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="order-card-footer">
                    <div class="order-store-info">
                        <i class="fas fa-store text-primary"></i> 
                        <span>{{ $order->store->name ?? 'Toko NusaMarket' }}</span>
                    </div>
                    <div>
                        <span class="text-xs text-muted mr-2">Total Transaksi:</span>
                        <span class="order-total-price">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif
@endsection
