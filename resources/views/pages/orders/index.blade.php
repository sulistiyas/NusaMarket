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
    <div class="flex flex-col gap-4">
        @foreach($orders as $order)
            <div class="card p-5">
                <div class="flex flex-wrap justify-between items-center border-b pb-3 mb-3 gap-2">
                    <div>
                        <span class="font-bold text-primary mr-2">#{{ $order->order_number }}</span>
                        <span class="text-xs text-muted">{{ $order->created_at->format('d M Y H:i') }}</span>
                    </div>

                    <div class="flex items-center gap-2">
                        @if($order->status === 'pending')
                            <span class="badge badge-warning">Menunggu Konfirmasi</span>
                        @elseif($order->status === 'processing')
                            <span class="badge badge-info">Diproses</span>
                        @elseif($order->status === 'completed')
                            <span class="badge badge-success">Selesai</span>
                        @else
                            <span class="badge badge-danger">Dibatalkan</span>
                        @endif

                        <a href="{{ route('orders.show', $order->id) }}" class="btn btn-ghost btn-sm">
                            Detail <i class="fas fa-chevron-right ml-1"></i>
                        </a>
                    </div>
                </div>

                {{-- Items Preview --}}
                <div class="flex flex-col gap-2 mb-4">
                    @foreach($order->items as $item)
                        <div class="flex justify-between items-center text-sm">
                            <div>
                                <span class="font-medium">{{ $item->product_name }}</span>
                                <span class="text-muted text-xs">x {{ $item->quantity }}</span>
                            </div>
                            <div class="font-semibold">
                                Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="flex justify-between items-center border-t pt-3">
                    <div class="text-xs text-muted">
                        <i class="fas fa-store mr-1"></i> {{ $order->store->name ?? 'Toko NusaMarket' }}
                    </div>
                    <div class="text-right">
                        <span class="text-xs text-muted mr-2">Total Transaksi:</span>
                        <span class="font-bold text-lg text-primary">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif
@endsection
