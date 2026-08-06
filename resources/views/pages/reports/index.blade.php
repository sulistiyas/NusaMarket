@extends('layouts.app')

@section('title', 'Laporan & Analitik — ' . config('app.name', 'NusaMarket'))

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/datatable.css') }}">
    <link rel="stylesheet" href="{{ asset('css/report.css') }}">
    <link rel="stylesheet" href="{{ asset('css/badge.css') }}">
@endpush

@section('content')

{{-- ============================================================
     PAGE HEADER
     ============================================================ --}}
<div class="report-page-header">
    <div class="report-page-title">
        <h1><i class="fas fa-chart-bar" style="color: var(--primary); margin-right: 10px;"></i>Laporan & Analitik</h1>
        <p>Ringkasan kinerja bisnis NusaMarket — data real-time dari database.</p>
    </div>

    <div class="report-export-group">
        <a id="btn-export-pdf"
           href="{{ route('reports.export', 'pdf') }}?start_date={{ $startDate }}&end_date={{ $endDate }}"
           class="btn-export-pdf"
           target="_blank">
            <i class="fas fa-file-pdf"></i> Export PDF
        </a>
        <a id="btn-export-excel"
           href="{{ route('reports.export', 'excel') }}?start_date={{ $startDate }}&end_date={{ $endDate }}"
           class="btn-export-excel">
            <i class="fas fa-file-excel"></i> Export Excel
        </a>
    </div>
</div>

{{-- ============================================================
     FILTER DATE RANGE
     ============================================================ --}}
<form id="report-filter-form" method="GET" action="{{ route('reports.index') }}">
    <div class="report-filter-bar">
        <i class="fas fa-filter" style="color: var(--primary);"></i>
        <label for="start_date">Dari:</label>
        <input type="date" id="start_date" name="start_date" value="{{ $startDate }}"
               data-export-param="start_date">
        <span class="report-filter-separator">—</span>
        <label for="end_date">Sampai:</label>
        <input type="date" id="end_date" name="end_date" value="{{ $endDate }}"
               data-export-param="end_date">

        <input type="hidden" name="year" id="filter-year" value="{{ $year }}">

        <div class="report-filter-actions">
            <button type="submit" class="btn btn-primary btn-sm" id="btn-filter-apply">
                <i class="fas fa-search"></i> Terapkan Filter
            </button>
            <a href="{{ route('reports.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-undo"></i> Reset
            </a>
        </div>
    </div>
</form>

{{-- ============================================================
     SUMMARY STAT CARDS
     ============================================================ --}}
<div class="report-summary-grid">
    {{-- Revenue --}}
    <div class="report-stat-card blue">
        <div class="report-stat-icon"><i class="fas fa-wallet"></i></div>
        <div class="report-stat-label">Total Revenue</div>
        <div class="report-stat-value">Rp {{ number_format($summary['total_revenue'], 0, ',', '.') }}</div>
        <div class="report-stat-sub">Periode {{ \Carbon\Carbon::parse($startDate)->format('d M') }} – {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</div>
    </div>

    {{-- Total Orders --}}
    <div class="report-stat-card purple">
        <div class="report-stat-icon"><i class="fas fa-shopping-bag"></i></div>
        <div class="report-stat-label">Total Pesanan</div>
        <div class="report-stat-value">{{ number_format($summary['total_orders']) }}</div>
        <div class="report-stat-sub">{{ number_format($summary['completed_orders']) }} pesanan selesai</div>
    </div>

    {{-- Avg Order Value --}}
    <div class="report-stat-card teal">
        <div class="report-stat-icon"><i class="fas fa-receipt"></i></div>
        <div class="report-stat-label">Rata-rata Pesanan</div>
        <div class="report-stat-value">Rp {{ number_format($summary['avg_order_value'], 0, ',', '.') }}</div>
        <div class="report-stat-sub">Nilai rata-rata per transaksi</div>
    </div>

    {{-- Top Product --}}
    <div class="report-stat-card orange">
        <div class="report-stat-icon"><i class="fas fa-fire"></i></div>
        <div class="report-stat-label">Produk Terlaris</div>
        <div class="report-stat-value" style="font-size: var(--text-base);" title="{{ $summary['top_product']['name'] }}">
            {{ \Illuminate\Support\Str::limit($summary['top_product']['name'], 18) }}
        </div>
        <div class="report-stat-sub">{{ number_format($summary['top_product']['total_qty']) }} unit terjual</div>
    </div>

    {{-- New Users --}}
    <div class="report-stat-card green">
        <div class="report-stat-icon"><i class="fas fa-user-plus"></i></div>
        <div class="report-stat-label">Pengguna Baru</div>
        <div class="report-stat-value">{{ number_format($summary['new_users']) }}</div>
        <div class="report-stat-sub">Registrasi periode ini</div>
    </div>
</div>

{{-- ============================================================
     ROW 1 CHARTS: Revenue Line + Status Donut
     ============================================================ --}}
<div class="report-chart-row" style="margin-bottom: 24px;">
    {{-- Line Chart — Revenue per Bulan --}}
    <div class="report-chart-card">
        <div class="report-chart-header">
            <div>
                <div class="report-chart-title">
                    <i class="fas fa-chart-line"></i> Tren Revenue Bulanan
                </div>
                <div class="report-chart-subtitle">Total pendapatan per bulan dalam satu tahun</div>
            </div>
            <select id="year-revenue" class="report-year-select" data-chart="revenue">
                @for($y = now()->year; $y >= now()->year - 3; $y--)
                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>
        </div>
        <div class="report-chart-body">
            <canvas id="chart-revenue" height="100"></canvas>
        </div>
    </div>

    {{-- Donut Chart — Status Pesanan --}}
    <div class="report-chart-card">
        <div class="report-chart-header">
            <div>
                <div class="report-chart-title">
                    <i class="fas fa-chart-pie"></i> Status Pesanan
                </div>
                <div class="report-chart-subtitle">Distribusi status dalam rentang tanggal</div>
            </div>
        </div>
        <div class="report-chart-body">
            <canvas id="chart-status" height="150"></canvas>
        </div>
    </div>
</div>

{{-- ============================================================
     ROW 2 CHARTS: Top Products Bar + User Growth Bar
     ============================================================ --}}
<div class="report-chart-row equal" style="margin-bottom: 24px;">
    {{-- Bar Chart — Top 5 Produk Terlaris --}}
    <div class="report-chart-card">
        <div class="report-chart-header">
            <div>
                <div class="report-chart-title">
                    <i class="fas fa-trophy"></i> Top 5 Produk Terlaris
                </div>
                <div class="report-chart-subtitle">Berdasarkan total unit terjual</div>
            </div>
        </div>
        <div class="report-chart-body">
            <canvas id="chart-top-products" height="180"></canvas>
        </div>
    </div>

    {{-- Bar Chart — Pertumbuhan Pengguna --}}
    <div class="report-chart-card">
        <div class="report-chart-header">
            <div>
                <div class="report-chart-title">
                    <i class="fas fa-users"></i> Pertumbuhan Pengguna
                </div>
                <div class="report-chart-subtitle">Registrasi pengguna baru per bulan</div>
            </div>
            <select id="year-users" class="report-year-select" data-chart="users">
                @for($y = now()->year; $y >= now()->year - 3; $y--)
                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>
        </div>
        <div class="report-chart-body">
            <canvas id="chart-user-growth" height="180"></canvas>
        </div>
    </div>
</div>

{{-- ============================================================
     PERIOD SUMMARY TABLE
     ============================================================ --}}
<div class="report-period-card"
     x-data="reportTable({
         startDate: '{{ $startDate }}',
         endDate:   '{{ $endDate }}',
         groupBy:   'daily'
     })"
     x-init="fetchData()">

    <div class="report-period-header">
        <div>
            <div class="report-chart-title">
                <i class="fas fa-table" style="color: var(--primary);"></i> Ringkasan per Periode
            </div>
            <div class="report-chart-subtitle">Ringkasan revenue & pesanan berdasarkan periode yang dipilih</div>
        </div>
        <div class="report-period-toggle">
            <button class="period-tab" :class="groupBy === 'daily' ? 'active' : ''"
                    @click="groupBy = 'daily'; fetchData()">Harian</button>
            <button class="period-tab" :class="groupBy === 'weekly' ? 'active' : ''"
                    @click="groupBy = 'weekly'; fetchData()">Mingguan</button>
            <button class="period-tab" :class="groupBy === 'monthly' ? 'active' : ''"
                    @click="groupBy = 'monthly'; fetchData()">Bulanan</button>
        </div>
    </div>

    <div class="dt-responsive" style="border: none; border-radius: 0; box-shadow: none;">
        <table class="dt-table">
            <thead>
                <tr>
                    <th>Periode</th>
                    <th class="text-right">Total Pesanan</th>
                    <th class="text-right">Revenue</th>
                    <th class="text-right">Selesai</th>
                    <th class="text-right">Dibatalkan</th>
                </tr>
            </thead>
            <tbody>
                {{-- Loading state --}}
                <template x-if="loading">
                    <tr>
                        <td colspan="5">
                            <div class="chart-loading">
                                <div class="spinner"></div>
                                <span>Memuat data...</span>
                            </div>
                        </td>
                    </tr>
                </template>

                {{-- Data rows --}}
                <template x-if="!loading && rows.length > 0">
                    <template x-for="row in rows" :key="row.period">
                        <tr>
                            <td>
                                <span style="font-weight: 600; color: var(--primary-deeper);" x-text="row.period"></span>
                            </td>
                            <td class="text-right">
                                <span class="badge badge-info" x-text="row.total_orders"></span>
                            </td>
                            <td class="text-right">
                                <span style="font-weight: 700; color: var(--primary-deeper);"
                                      x-text="'Rp ' + Number(row.total_revenue).toLocaleString('id-ID')"></span>
                            </td>
                            <td class="text-right">
                                <span class="badge badge-success" x-text="row.completed"></span>
                            </td>
                            <td class="text-right">
                                <span class="badge badge-danger" x-text="row.cancelled"></span>
                            </td>
                        </tr>
                    </template>
                </template>

                {{-- Empty state --}}
                <template x-if="!loading && rows.length === 0">
                    <tr>
                        <td colspan="5">
                            <div class="report-empty">
                                <i class="fas fa-inbox"></i>
                                <div class="report-empty-title">Tidak Ada Data</div>
                                <p>Tidak ada transaksi dalam rentang tanggal yang dipilih.</p>
                            </div>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>
</div>

@endsection

@push('scripts')
{{-- Chart.js CDN --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script src="{{ asset('js/report.js') }}" data-start-date="{{ $startDate }}" data-end-date="{{ $endDate }}" data-year="{{ $year }}"></script>
@endpush
