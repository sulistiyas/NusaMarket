@extends('layouts.app')

@section('title', 'Detail Pesanan #' . $order->order_number . ' — NusaMarket')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/order.css') }}">
@endpush

@section('content')
{{-- 1. Page Head --}}
<div class="order-detail-header">
    <div>
        <h1 class="order-detail-title">
            Detail Pesanan <span class="order-detail-code">#{{ $order->order_number }}</span>
        </h1>
        <p class="order-detail-subtitle">
            <i class="ri-calendar-event-line"></i>
            <span>Tanggal Transaksi: {{ $order->created_at->format('d M Y H:i:s') }}</span>
        </p>
    </div>

    <a href="{{ route('orders.index') }}" class="btn btn-secondary inline-flex items-center gap-2">
        <i class="ri-arrow-left-line"></i> Kembali
    </a>
</div>

{{-- 2. Two-Column Layout (<900px single column) --}}
<div class="order-detail-layout">
    {{-- Left Column: Product List + Address & Shipping --}}
    <div>
        {{-- 3. Card "Rincian Produk" --}}
        <div class="detail-card">
            <h3 class="detail-card-title">
                <i class="ri-box-3-line"></i> Rincian Produk
            </h3>

            <div class="product-detail-list">
                @foreach($order->items as $item)
                    <div class="product-detail-item">
                        <div class="product-detail-left">
                            <div class="product-detail-thumb">
                                <i class="ri-box-3-fill"></i>
                            </div>
                            <div class="product-detail-info">
                                <div class="product-detail-name">{{ $item->product_name }}</div>
                                <div class="product-detail-meta">
                                    Rp {{ number_format($item->price, 0, ',', '.') }} × {{ $item->quantity }}
                                </div>
                            </div>
                        </div>
                        <div class="product-detail-subtotal">
                            Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="product-detail-summary">
                <span>Total {{ $order->items->count() }} Jenis Produk ({{ $order->items->sum('quantity') }} items)</span>
                <div class="product-detail-summary-value">
                    Rp {{ number_format($order->items->sum('subtotal'), 0, ',', '.') }}
                </div>
            </div>
        </div>

        {{-- 4. Card "Info Alamat & Pengiriman" --}}
        <div class="detail-card">
            <h3 class="detail-card-title">
                <i class="ri-truck-line"></i> Info Alamat & Pengiriman
            </h3>

            @php $addr = $order->shipping_address ?? []; @endphp
            <div class="info-rows-container">
                <div class="info-row-item">
                    <div class="info-row-icon">
                        <i class="ri-user-3-line"></i>
                    </div>
                    <div class="info-row-content">
                        <span class="info-row-label">Penerima & Telepon</span>
                        <span class="info-row-value">
                            {{ $addr['recipient_name'] ?? '-' }} 
                            @if(!empty($addr['phone']))
                                ({{ $addr['phone'] }})
                            @endif
                        </span>
                    </div>
                </div>

                <div class="info-row-item">
                    <div class="info-row-icon">
                        <i class="ri-map-pin-line"></i>
                    </div>
                    <div class="info-row-content">
                        <span class="info-row-label">Alamat Lengkap</span>
                        <span class="info-row-value">
                            {{ $addr['address'] ?? '-' }}
                            @if(!empty($addr['city'])) , {{ $addr['city'] }} @endif
                            @if(!empty($addr['postal_code'])) , {{ $addr['postal_code'] }} @endif
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Right Column: Status Card + Payment Summary --}}
    <div>
        {{-- 5. Card "Status Pesanan" --}}
        <div class="detail-card">
            <h3 class="detail-card-title">
                <i class="ri-time-line"></i> Status Pesanan
            </h3>

            {{-- Big Status Badge --}}
            @if($order->status === 'pending')
                <div class="status-badge-lg status-badge-pending">Menunggu Konfirmasi</div>
            @elseif($order->status === 'processing')
                <div class="status-badge-lg status-badge-processing">Diproses & Dikemas</div>
            @elseif($order->status === 'completed')
                <div class="status-badge-lg status-badge-completed">Pesanan Selesai</div>
            @else
                <div class="status-badge-lg status-badge-cancelled">Pesanan Dibatalkan</div>
            @endif

            {{-- Dynamic 4-Step Vertical Timeline --}}
            @php
                $orderStatus = $order->status;
                
                // Timeline steps configuration
                // 1: Pesanan Dibuat, 2: Menunggu Konfirmasi, 3: Diproses & Dikemas, 4: Selesai
                $stepStatusMap = [
                    'pending' => 2,
                    'processing' => 3,
                    'completed' => 4,
                    'cancelled' => -1,
                ];
                
                $currentStepLevel = $stepStatusMap[$orderStatus] ?? 1;
            @endphp

            <div class="order-timeline">
                @if($orderStatus === 'cancelled')
                    <div class="timeline-step timeline-step-passed">
                        <div class="timeline-dot"><i class="ri-check-line"></i></div>
                        <div class="timeline-title">Pesanan Dibuat</div>
                        <div class="timeline-desc">Transaksi berhasil dibuat</div>
                    </div>
                    <div class="timeline-step timeline-step-cancelled">
                        <div class="timeline-dot"><i class="ri-close-line"></i></div>
                        <div class="timeline-title">Pesanan Dibatalkan</div>
                        <div class="timeline-desc">Transaksi ini telah dibatalkan</div>
                    </div>
                @else
                    {{-- Step 1: Pesanan Dibuat --}}
                    <div class="timeline-step {{ $currentStepLevel >= 1 ? ($currentStepLevel == 1 ? 'timeline-step-current' : 'timeline-step-passed') : 'timeline-step-future' }}">
                        <div class="timeline-dot">
                            @if($currentStepLevel > 1)<i class="ri-check-line"></i>@elseif($currentStepLevel == 1)<i class="ri-time-line"></i>@endif
                        </div>
                        <div class="timeline-title">Pesanan Dibuat</div>
                        <div class="timeline-desc">Transaksi telah diterbitkan</div>
                    </div>

                    {{-- Step 2: Menunggu Konfirmasi --}}
                    <div class="timeline-step {{ $currentStepLevel >= 2 ? ($currentStepLevel == 2 ? 'timeline-step-current' : 'timeline-step-passed') : 'timeline-step-future' }}">
                        <div class="timeline-dot">
                            @if($currentStepLevel > 2)<i class="ri-check-line"></i>@elseif($currentStepLevel == 2)<i class="ri-time-line"></i>@endif
                        </div>
                        <div class="timeline-title">Menunggu Konfirmasi</div>
                        <div class="timeline-desc">Menunggu persetujuan penjual</div>
                    </div>

                    {{-- Step 3: Diproses & Dikemas --}}
                    <div class="timeline-step {{ $currentStepLevel >= 3 ? ($currentStepLevel == 3 ? 'timeline-step-current' : 'timeline-step-passed') : 'timeline-step-future' }}">
                        <div class="timeline-dot">
                            @if($currentStepLevel > 3)<i class="ri-check-line"></i>@elseif($currentStepLevel == 3)<i class="ri-time-line"></i>@endif
                        </div>
                        <div class="timeline-title">Diproses & Dikemas</div>
                        <div class="timeline-desc">Penjual sedang menyiapkan barang</div>
                    </div>

                    {{-- Step 4: Selesai --}}
                    <div class="timeline-step {{ $currentStepLevel >= 4 ? 'timeline-step-passed' : 'timeline-step-future' }}">
                        <div class="timeline-dot">
                            @if($currentStepLevel >= 4)<i class="ri-check-line"></i>@endif
                        </div>
                        <div class="timeline-title">Selesai</div>
                        <div class="timeline-desc">Pesanan telah diterima pelanggan</div>
                    </div>
                @endif
            </div>

            {{-- Dropdown Change Status & Submit Button --}}
            <div class="status-update-box">
                <form id="update-status-form" action="{{ route('orders.updateStatus', $order->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="status-update-form-group">
                        <label for="status-select">Ubah Status Pesanan:</label>
                        <select id="status-select" name="status" class="status-select-control">
                            <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending (Menunggu Konfirmasi)</option>
                            <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing (Diproses)</option>
                            <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Completed (Selesai)</option>
                            <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled (Dibatalkan)</option>
                        </select>
                        
                        <button type="button" id="btn-submit-status" class="btn-submit-status">
                            <i class="ri-save-3-line"></i> Simpan Status
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- 6. Card "Rincian Pembayaran" --}}
        <div class="detail-card">
            <h3 class="detail-card-title">
                <i class="ri-wallet-3-line"></i> Rincian Pembayaran
            </h3>

            <div class="payment-detail-rows">
                <div class="payment-detail-row">
                    <span>Subtotal Produk</span>
                    <span class="payment-detail-value">Rp {{ number_format($order->total_amount - $order->shipping_fee, 0, ',', '.') }}</span>
                </div>

                <div class="payment-detail-row">
                    <span>Ongkos Kirim</span>
                    <span class="payment-detail-value">Rp {{ number_format($order->shipping_fee, 0, ',', '.') }}</span>
                </div>

                <hr class="payment-total-divider">

                <div class="payment-total-row">
                    <span class="payment-total-label">Total Bayar</span>
                    <span class="payment-total-value">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const btnSubmit = document.getElementById('btn-submit-status');
    const form = document.getElementById('update-status-form');

    if (btnSubmit && form) {
        btnSubmit.addEventListener('click', function (e) {
            e.preventDefault();
            const selectEl = document.getElementById('status-select');
            const selectedText = selectEl.options[selectEl.selectedIndex].text;

            if (typeof window.Alert !== 'undefined' && typeof window.Alert.confirm === 'function') {
                window.Alert.confirm(
                    'Konfirmasi Perubahan Status',
                    'Apakah Anda yakin ingin mengubah status pesanan ini menjadi "' + selectedText + '"?',
                    function () {
                        form.submit();
                    }
                );
            } else {
                if (confirm('Apakah Anda yakin ingin mengubah status pesanan ini?')) {
                    form.submit();
                }
            }
        });
    }
});
</script>
@endpush

