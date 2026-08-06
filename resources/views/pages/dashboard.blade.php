@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="mb-4">
    <h1>Dashboard Analytics</h1>
    <p>Selamat datang di NusaMarket Console Dashboard.</p>
</div>

{{-- Metric Cards Grid --}}
<div class="grid grid-cols-1 grid-cols-sm-2 grid-cols-lg-4 mb-4">
    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-wallet"></i>
        </div>
        <div>
            <div class="stat-value">Rp {{ number_format($totalSales ?? 0, 0, ',', '.') }}</div>
            <div class="stat-label">Total Penjualan</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon ocean">
            <i class="fas fa-shopping-bag"></i>
        </div>
        <div>
            <div class="stat-value">{{ $totalOrders ?? 0 }}</div>
            <div class="stat-label">Total Pesanan</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-box"></i>
        </div>
        <div>
            <div class="stat-value">{{ $totalProducts ?? 0 }}</div>
            <div class="stat-label">Produk Aktif</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon ocean">
            <i class="fas fa-users"></i>
        </div>
        <div>
            <div class="stat-value">{{ $totalUsers ?? 0 }}</div>
            <div class="stat-label">Pengguna Terdaftar</div>
        </div>
    </div>
</div>

{{-- Recent Transactions Card --}}
<div class="card">
    <div class="card-header">
        <h2 class="card-title"><i class="fas fa-clock"></i> Transaksi Terbaru</h2>
        <a href="{{ route('products.index') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-box-open"></i> Kelola Produk
        </a>
    </div>
    <div class="card-body" style="padding: 0;">
        <div class="dt-responsive">
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
                                <span class="badge badge-success">{{ strtoupper($order->status) }}</span>
                            </td>
                            <td>{{ $order->created_at->format('d M Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center" style="padding: 30px; color: var(--text-muted);">
                                <i class="fas fa-inbox fa-2x mb-2" style="display: block;"></i>
                                Belum ada transaksi terbaru.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
