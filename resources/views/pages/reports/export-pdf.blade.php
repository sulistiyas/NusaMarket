<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan NusaMarket — {{ $startDate }} s/d {{ $endDate }}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 11px;
            color: #1a1a2e;
            background: #fff;
            padding: 24px;
        }

        /* Header */
        .pdf-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 3px solid #1e6fd9;
            padding-bottom: 16px;
            margin-bottom: 24px;
        }
        .pdf-brand {
            font-size: 22px;
            font-weight: 900;
            color: #0b3a75;
        }
        .pdf-brand span { color: #1e6fd9; }
        .pdf-meta { text-align: right; color: #64748b; font-size: 10px; }
        .pdf-meta .period {
            font-size: 12px;
            font-weight: 700;
            color: #1e6fd9;
        }

        /* Summary Cards */
        .summary-grid {
            display: table;
            width: 100%;
            margin-bottom: 24px;
            border-collapse: separate;
            border-spacing: 8px;
        }
        .summary-row { display: table-row; }
        .summary-cell {
            display: table-cell;
            width: 20%;
            background: #f0f6ff;
            border: 1px solid #bfdbfe;
            border-radius: 8px;
            padding: 12px;
            vertical-align: middle;
        }
        .summary-label {
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #64748b;
            margin-bottom: 4px;
        }
        .summary-value {
            font-size: 14px;
            font-weight: 900;
            color: #0b3a75;
        }
        .summary-sub {
            font-size: 9px;
            color: #94a3b8;
            margin-top: 2px;
        }

        /* Section title */
        .section-title {
            font-size: 13px;
            font-weight: 800;
            color: #0b3a75;
            border-left: 4px solid #1e6fd9;
            padding-left: 10px;
            margin-bottom: 14px;
            margin-top: 20px;
        }

        /* Table */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
        }
        .data-table thead tr {
            background: #0b3a75;
            color: #fff;
        }
        .data-table th {
            padding: 9px 10px;
            text-align: left;
            font-weight: 700;
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .data-table th.text-right,
        .data-table td.text-right { text-align: right; }
        .data-table tbody tr:nth-child(even) { background: #f8faff; }
        .data-table tbody tr:nth-child(odd)  { background: #fff; }
        .data-table td { padding: 8px 10px; border-bottom: 1px solid #e2e8f0; }

        /* Status badge */
        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
        }
        .badge-pending    { background: #fef3c7; color: #92400e; }
        .badge-processing { background: #ede9fe; color: #4c1d95; }
        .badge-completed  { background: #dcfce7; color: #14532d; }
        .badge-cancelled  { background: #fee2e2; color: #7f1d1d; }

        /* Footer */
        .pdf-footer {
            margin-top: 32px;
            border-top: 1px solid #e2e8f0;
            padding-top: 12px;
            text-align: center;
            color: #94a3b8;
            font-size: 9px;
        }

        /* No data */
        .no-data {
            text-align: center;
            padding: 24px;
            color: #94a3b8;
            font-size: 11px;
        }
    </style>
</head>
<body>

    {{-- Header --}}
    <div class="pdf-header">
        <div>
            <div class="pdf-brand">Nusa<span>Market</span></div>
            <div style="font-size: 13px; font-weight: 700; color: #1e6fd9; margin-top: 2px;">Laporan & Analitik Bisnis</div>
        </div>
        <div class="pdf-meta">
            <div class="period">{{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} — {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</div>
            <div>Digenerate: {{ now()->format('d M Y, H:i') }} WIB</div>
            <div>Halaman 1</div>
        </div>
    </div>

    {{-- Summary Cards (menggunakan tabel karena dompdf tidak support flexbox penuh) --}}
    <table class="summary-grid">
        <tr class="summary-row">
            <td class="summary-cell">
                <div class="summary-label">Total Revenue</div>
                <div class="summary-value">Rp {{ number_format($summary['total_revenue'], 0, ',', '.') }}</div>
                <div class="summary-sub">Pesanan terbayar</div>
            </td>
            <td class="summary-cell">
                <div class="summary-label">Total Pesanan</div>
                <div class="summary-value">{{ number_format($summary['total_orders']) }}</div>
                <div class="summary-sub">{{ $summary['completed_orders'] }} selesai</div>
            </td>
            <td class="summary-cell">
                <div class="summary-label">Rata-rata Pesanan</div>
                <div class="summary-value">Rp {{ number_format($summary['avg_order_value'], 0, ',', '.') }}</div>
                <div class="summary-sub">Per transaksi</div>
            </td>
            <td class="summary-cell">
                <div class="summary-label">Produk Terlaris</div>
                <div class="summary-value" style="font-size: 11px;">{{ \Illuminate\Support\Str::limit($summary['top_product']['name'], 20) }}</div>
                <div class="summary-sub">{{ $summary['top_product']['total_qty'] }} unit terjual</div>
            </td>
            <td class="summary-cell">
                <div class="summary-label">Pengguna Baru</div>
                <div class="summary-value">{{ number_format($summary['new_users']) }}</div>
                <div class="summary-sub">Registrasi periode ini</div>
            </td>
        </tr>
    </table>

    {{-- Order Detail Table --}}
    <div class="section-title">Detail Pesanan</div>

    @if($orders->count() > 0)
        <table class="data-table">
            <thead>
                <tr>
                    <th>No. Pesanan</th>
                    <th>Pembeli</th>
                    <th>Email</th>
                    <th>Toko</th>
                    <th class="text-right">Total</th>
                    <th>Status</th>
                    <th>Pembayaran</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                    <tr>
                        <td><strong>{{ $order->order_number }}</strong></td>
                        <td>{{ $order->buyer->name ?? '-' }}</td>
                        <td>{{ $order->buyer->email ?? '-' }}</td>
                        <td>{{ $order->store->name ?? '-' }}</td>
                        <td class="text-right"><strong>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong></td>
                        <td>
                            <span class="badge badge-{{ $order->status }}">{{ strtoupper($order->status) }}</span>
                        </td>
                        <td>
                            <span class="badge badge-{{ $order->payment_status === 'paid' ? 'completed' : 'pending' }}">
                                {{ strtoupper($order->payment_status) }}
                            </span>
                        </td>
                        <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="no-data">Tidak ada pesanan dalam rentang tanggal yang dipilih.</div>
    @endif

    {{-- Footer --}}
    <div class="pdf-footer">
        NusaMarket — Laporan ini digenerate otomatis oleh sistem pada {{ now()->format('d M Y H:i') }} WIB.
        Dokumen ini bersifat konfidensial.
    </div>

</body>
</html>
