@extends('layouts.app')

@section('title', 'Detail Pesanan #' . $order->order_number . ' — NusaMarket')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-2xl font-bold">Detail Pesanan #{{ $order->order_number }}</h1>
        <p class="text-muted">Tanggal Transaksi: {{ $order->created_at->format('d M Y H:i WIB') }}</p>
    </div>

    <a href="{{ route('orders.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left mr-2"></i> Kembali
    </a>
</div>

<div class="cart-layout">
    <div>
        {{-- Item List Card --}}
        <div class="card p-6 mb-6">
            <h3 class="font-bold text-lg mb-4"><i class="fas fa-box text-primary mr-2"></i> Rincian Produk</h3>
            
            <div class="flex flex-col gap-3">
                @foreach($order->items as $item)
                    <div class="flex justify-between items-center border-b pb-3 last:border-0 last:pb-0">
                        <div>
                            <h4 class="font-semibold text-main">{{ $item->product_name }}</h4>
                            <div class="text-xs text-muted">
                                Rp {{ number_format($item->price, 0, ',', '.') }} x {{ $item->quantity }}
                            </div>
                        </div>
                        <div class="font-bold text-primary">
                            Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Shipping Address Card --}}
        <div class="card p-6">
            <h3 class="font-bold text-lg mb-4"><i class="fas fa-truck text-primary mr-2"></i> Info Alamat & Pengiriman</h3>
            
            @php $addr = $order->shipping_address ?? []; @endphp
            <div class="text-sm leading-relaxed">
                <p><strong>Penerima:</strong> {{ $addr['recipient_name'] ?? '-' }} ({{ $addr['phone'] ?? '-' }})</p>
                <p><strong>Alamat:</strong> {{ $addr['address'] ?? '-' }}, {{ $addr['city'] ?? '-' }}, {{ $addr['postal_code'] ?? '-' }}</p>
            </div>
        </div>
    </div>

    {{-- Order Summary & Status Update Action --}}
    <div>
        <div class="card p-6 mb-6">
            <h3 class="font-bold text-lg mb-4">Status Pesanan</h3>
            
            <div class="mb-4">
                @if($order->status === 'pending')
                    <span class="badge badge-warning w-full text-center py-2 text-sm display-block">Menunggu Konfirmasi</span>
                @elseif($order->status === 'processing')
                    <span class="badge badge-info w-full text-center py-2 text-sm display-block">Sedang Diproses</span>
                @elseif($order->status === 'completed')
                    <span class="badge badge-success w-full text-center py-2 text-sm display-block">Pesanan Selesai</span>
                @else
                    <span class="badge badge-danger w-full text-center py-2 text-sm display-block">Pesanan Dibatalkan</span>
                @endif
            </div>

            {{-- Update Status Action Form --}}
            <form action="{{ route('orders.updateStatus', $order->id) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="form-group mb-3">
                    <label class="form-label">Ubah Status Pesanan:</label>
                    <select name="status" class="form-control" onchange="this.form.submit()">
                        <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing</option>
                        <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
            </form>
        </div>

        <div class="cart-summary-card">
            <h3 class="font-bold text-lg mb-4">Rincian Pembayaran</h3>
            
            <div class="summary-row">
                <span>Subtotal Produk</span>
                <span>Rp {{ number_format($order->total_amount - $order->shipping_fee, 0, ',', '.') }}</span>
            </div>

            <div class="summary-row">
                <span>Ongkos Kirim</span>
                <span>Rp {{ number_format($order->shipping_fee, 0, ',', '.') }}</span>
            </div>

            <div class="summary-row grand-total">
                <span>Total Bayar</span>
                <span class="text-primary">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>
</div>
@endsection
