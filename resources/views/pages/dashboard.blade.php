@extends('layouts.app')

@section('title', 'Dashboard Analytics — ' . config('app.name', 'NusaMarket'))

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/datatable.css') }}">
    <link rel="stylesheet" href="{{ asset('css/card.css') }}">
    <link rel="stylesheet" href="{{ asset('css/badge.css') }}">
@endpush

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold">Dashboard Analytics</h1>
    <p class="text-muted">Selamat datang di NusaMarket Console Dashboard.</p>
</div>

{{-- 1. Redesigned 4 Stat Cards --}}
<div class="grid grid-cols-1 grid-cols-sm-2 grid-cols-lg-4 mb-6" style="gap: 18px;">
    {{-- Card 1: Total Penjualan (Blue) --}}
    <div class="stat-card stat-card-blue">
        <div class="stat-card-top">
            <span class="stat-label">Total Penjualan</span>
            <div class="stat-badge-icon">
                <i class="fas fa-wallet"></i>
            </div>
        </div>
        <div class="stat-card-middle">
            <div class="stat-value-hero">Rp {{ number_format($totalSales ?? 0, 0, ',', '.') }}</div>
        </div>
        <div class="stat-card-bottom">
            <span class="stat-micro-badge">
                <i class="fas fa-circle" style="font-size: 6px;"></i> Live Real-time
            </span>
            <div class="stat-sparkline" title="Tren Penjualan 7 Hari">
                <span style="height: 35%;"></span>
                <span style="height: 55%;"></span>
                <span style="height: 40%;"></span>
                <span style="height: 70%;"></span>
                <span style="height: 60%;" class="active"></span>
                <span style="height: 85%;" class="active"></span>
                <span style="height: 100%;" class="active"></span>
            </div>
        </div>
    </div>

    {{-- Card 2: Total Pesanan (Purple) --}}
    <div class="stat-card stat-card-purple">
        <div class="stat-card-top">
            <span class="stat-label">Total Pesanan</span>
            <div class="stat-badge-icon">
                <i class="fas fa-shopping-bag"></i>
            </div>
        </div>
        <div class="stat-card-middle">
            <div class="stat-value-hero">{{ number_format($totalOrders ?? 0) }}</div>
        </div>
        <div class="stat-card-bottom">
            <span class="stat-micro-badge">
                <i class="fas fa-check-circle"></i> Pesanan Terproses
            </span>
            <div class="stat-sparkline" title="Tren Pesanan 7 Hari">
                <span style="height: 40%;"></span>
                <span style="height: 30%;"></span>
                <span style="height: 60%;"></span>
                <span style="height: 50%;"></span>
                <span style="height: 75%;" class="active"></span>
                <span style="height: 90%;" class="active"></span>
                <span style="height: 70%;" class="active"></span>
            </div>
        </div>
    </div>

    {{-- Card 3: Produk Aktif (Navy) --}}
    <div class="stat-card stat-card-navy">
        <div class="stat-card-top">
            <span class="stat-label">Produk Aktif</span>
            <div class="stat-badge-icon">
                <i class="fas fa-box"></i>
            </div>
        </div>
        <div class="stat-card-middle">
            <div class="stat-value-hero">{{ number_format($totalProducts ?? 0) }}</div>
        </div>
        <div class="stat-card-bottom">
            <span class="stat-micro-badge">
                <i class="fas fa-tags"></i> Katalog Aktif
            </span>
            <div class="stat-sparkline" title="Pertumbuhan Produk">
                <span style="height: 50%;"></span>
                <span style="height: 65%;"></span>
                <span style="height: 45%;"></span>
                <span style="height: 80%;"></span>
                <span style="height: 65%;" class="active"></span>
                <span style="height: 80%;" class="active"></span>
                <span style="height: 95%;" class="active"></span>
            </div>
        </div>
    </div>

    {{-- Card 4: Pengguna Terdaftar (Green) --}}
    <div class="stat-card stat-card-green">
        <div class="stat-card-top">
            <span class="stat-label">Pengguna Terdaftar</span>
            <div class="stat-badge-icon">
                <i class="fas fa-users"></i>
            </div>
        </div>
        <div class="stat-card-middle">
            <div class="stat-value-hero">{{ number_format($totalUsers ?? 0) }}</div>
        </div>
        <div class="stat-card-bottom">
            <span class="stat-micro-badge">
                <i class="fas fa-user-check"></i> Akun Terverifikasi
            </span>
            <div class="stat-sparkline" title="Pertumbuhan Pengguna">
                <span style="height: 30%;"></span>
                <span style="height: 45%;"></span>
                <span style="height: 55%;"></span>
                <span style="height: 60%;"></span>
                <span style="height: 70%;" class="active"></span>
                <span style="height: 85%;" class="active"></span>
                <span style="height: 100%;" class="active"></span>
            </div>
        </div>
    </div>
</div>

{{-- 2. Redesigned "Transaksi Terbaru" Panel --}}
<div class="card">
    <div class="card-header flex-wrap" style="gap: 16px;">
        <div class="flex flex-col">
            <h2 class="card-title">
                <i class="fas fa-clock"></i> Transaksi Terbaru
            </h2>
            <div class="card-subtitle">
                Total {{ number_format($recentOrders->total()) }} transaksi pesanan terbaru
            </div>
        </div>

        <div class="flex items-center gap-3 flex-wrap">
            {{-- Filter Status Tabs --}}
            <div class="dashboard-filter-tabs">
                <a href="{{ route('dashboard') }}" class="dashboard-tab-item {{ empty($status) ? 'active' : '' }}">
                    Semua
                </a>
                <a href="{{ route('dashboard', ['status' => 'pending']) }}" class="dashboard-tab-item {{ $status === 'pending' ? 'active' : '' }}">
                    Pending
                </a>
                <a href="{{ route('dashboard', ['status' => 'completed']) }}" class="dashboard-tab-item {{ $status === 'completed' ? 'active' : '' }}">
                    Selesai
                </a>
            </div>

            <a href="{{ route('products.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-box-open"></i> Kelola Produk
            </a>
        </div>
    </div>

    <div class="card-body" style="padding: 0;">
        <div class="dt-responsive" style="border: none; border-radius: 0; box-shadow: none;">
            <table class="dt-table">
                <thead>
                    <tr>
                        <th>No. Pesanan</th>
                        <th>Pembeli</th>
                        <th>Toko</th>
                        <th class="text-right">Total</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentOrders as $order)
                        <tr>
                            <td>
                                <strong>#{{ $order->order_number }}</strong>
                            </td>
                            <td>
                                <div class="buyer-cell">
                                    <div class="avatar-circle">
                                        {{ strtoupper(substr($order->buyer->name ?? 'P', 0, 1)) }}
                                    </div>
                                    <span>{{ $order->buyer->name ?? 'Pembeli Anonim' }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="chip-store">
                                    <i class="fas fa-store"></i>
                                    {{ $order->store->name ?? 'Toko NusaMarket' }}
                                </span>
                            </td>
                            <td class="text-right">
                                <div class="font-mono font-bold" style="color: var(--primary-deeper);">
                                    Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                </div>
                            </td>
                            <td>
                                @if($order->status === 'pending')
                                    <span class="badge badge-warning">
                                        <span class="badge-dot pulse"></span> PENDING
                                    </span>
                                @elseif($order->status === 'processing')
                                    <span class="badge badge-purple">
                                        <span class="badge-dot pulse"></span> PROCESSING
                                    </span>
                                @elseif($order->status === 'completed')
                                    <span class="badge badge-success">
                                        <span class="badge-dot"></span> COMPLETED
                                    </span>
                                @else
                                    <span class="badge badge-danger">
                                        CANCELLED
                                    </span>
                                @endif
                            </td>
                            <td>
                                <span style="color: var(--text-muted); font-size: var(--text-xs);">
                                    {{ $order->created_at->format('d M Y H:i') }}
                                </span>
                            </td>
                            <td class="text-right">
                                <a href="{{ route('orders.show', $order->id) }}" class="btn-action-icon" title="Lihat Detail Pesanan">
                                    <i class="fas fa-arrow-right"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center" style="padding: 48px 24px; color: var(--text-muted);">
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

    {{-- Footer Pagination --}}
    @if($recentOrders->hasPages() || $recentOrders->total() > 0)
        <div style="border-top: 1px solid var(--border);">
            {{ $recentOrders->links() }}
        </div>
    @endif
</div>
@endsection
