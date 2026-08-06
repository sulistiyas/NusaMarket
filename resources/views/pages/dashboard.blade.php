@extends('layouts.app')

@section('title', 'Dashboard')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/datatable.css') }}">
@endpush

@section('content')
<div class="mb-6">
    <h1>Dashboard Analytics</h1>
    <p>Selamat datang di NusaMarket Console Dashboard.</p>
</div>

{{-- Metric Cards Grid --}}
<div class="grid grid-cols-1 grid-cols-sm-2 grid-cols-lg-4 mb-6">
    <div class="stat-card">
        <div class="stat-content">
            <div class="stat-label">Total Penjualan</div>
            <div class="stat-value">Rp {{ number_format($totalSales ?? 0, 0, ',', '.') }}</div>
            <div class="stat-trend up">
                <i class="fas fa-arrow-up"></i> Live Real-time
            </div>
        </div>
        <div class="stat-icon">
            <i class="fas fa-wallet"></i>
        </div>
    </div>

    <div class="stat-card ocean">
        <div class="stat-content">
            <div class="stat-label">Total Pesanan</div>
            <div class="stat-value">{{ $totalOrders ?? 0 }}</div>
            <div class="stat-trend up">
                <i class="fas fa-shopping-bag"></i> Pesanan Terproses
            </div>
        </div>
        <div class="stat-icon ocean">
            <i class="fas fa-shopping-bag"></i>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-content">
            <div class="stat-label">Produk Aktif</div>
            <div class="stat-value">{{ $totalProducts ?? 0 }}</div>
            <div class="stat-trend up">
                <i class="fas fa-box"></i> Katalog Aktif
            </div>
        </div>
        <div class="stat-icon">
            <i class="fas fa-box"></i>
        </div>
    </div>

    <div class="stat-card ocean">
        <div class="stat-content">
            <div class="stat-label">Pengguna Terdaftar</div>
            <div class="stat-value">{{ $totalUsers ?? 0 }}</div>
            <div class="stat-trend up">
                <i class="fas fa-users"></i> Akun Terverifikasi
            </div>
        </div>
        <div class="stat-icon ocean">
            <i class="fas fa-users"></i>
        </div>
    </div>
</div>

{{-- Recent Transactions Card --}}
<div class="card">
    <div class="card-header">
        <h2 class="card-title"><i class="fas fa-clock"></i> Transaksi Terbaru</h2>
        <a href="{{ route('products.index') }}" class="btn btn-primary btn-sm btn-pill">
            <i class="fas fa-box-open"></i> Kelola Produk
        </a>
    </div>
    <div class="card-body" style="padding: 0;">
        <div class="dt-responsive" style="border: none; border-radius: 0; box-shadow: none;">
            <table class="dt-table">
                <thead>
                    <tr>
                        <th>No. Pesanan</th>
                        <th>Pembeli</th>
                        <th>Toko</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentOrders ?? [] as $order)
                        <tr>
                            <td><strong>{{ $order->order_number }}</strong></td>
                            <td>{{ $order->buyer->name ?? 'N/A' }}</td>
                            <td>{{ $order->store->name ?? 'N/A' }}</td>
                            <td>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                            <td>
                                <span class="badge badge-success">
                                    <span class="badge-dot pulse"></span>
                                    {{ strtoupper($order->status) }}
                                </span>
                            </td>
                            <td>{{ $order->created_at->format('d M Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center" style="padding: 48px 24px; color: var(--text-muted);">
                                <div style="width: 56px; height: 56px; margin: 0 auto 14px; border-radius: var(--radius-xl); background: var(--primary-pale); color: var(--primary); display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                                    <i class="fas fa-inbox"></i>
                                </div>
                                <div style="font-weight: 700; font-size: 1rem; color: var(--primary-deeper); margin-bottom: 4px;">Belum Ada Transaksi Terbaru</div>
                                <p style="margin: 0; font-size: 0.85rem;">Transaksi pesanan terbaru dari pembeli akan muncul di sini secara otomatis.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
