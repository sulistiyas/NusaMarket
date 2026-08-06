<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportService
{
    /**
     * Ringkasan statistik utama dalam rentang tanggal.
     */
    public function getSummaryStats(?string $startDate = null, ?string $endDate = null): array
    {
        $start = $startDate ? Carbon::parse($startDate)->startOfDay() : Carbon::now()->startOfMonth();
        $end   = $endDate   ? Carbon::parse($endDate)->endOfDay()   : Carbon::now()->endOfDay();

        $totalRevenue = Order::where('payment_status', 'paid')
            ->whereBetween('created_at', [$start, $end])
            ->sum('total_amount');

        $totalOrders = Order::whereBetween('created_at', [$start, $end])->count();

        $avgOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;

        $topProduct = OrderItem::select('product_id', DB::raw('SUM(quantity) as total_qty'))
            ->whereBetween('created_at', [$start, $end])
            ->groupBy('product_id')
            ->orderByDesc('total_qty')
            ->with('product:id,name')
            ->first();

        $newUsers = User::whereBetween('created_at', [$start, $end])->count();

        $completedOrders = Order::where('status', 'completed')
            ->whereBetween('created_at', [$start, $end])
            ->count();

        return [
            'total_revenue'   => (float) $totalRevenue,
            'total_orders'    => $totalOrders,
            'avg_order_value' => (float) $avgOrderValue,
            'top_product'     => $topProduct ? [
                'name'      => $topProduct->product->name ?? 'N/A',
                'total_qty' => (int) $topProduct->total_qty,
            ] : ['name' => 'N/A', 'total_qty' => 0],
            'new_users'        => $newUsers,
            'completed_orders' => $completedOrders,
        ];
    }

    /**
     * Tren revenue per bulan dalam satu tahun.
     */
    public function getMonthlyRevenue(int $year): array
    {
        $rows = Order::where('payment_status', 'paid')
            ->whereYear('created_at', $year)
            ->select(
                DB::raw('EXTRACT(MONTH FROM created_at)::int AS month'),
                DB::raw('SUM(total_amount) as revenue')
            )
            ->groupBy(DB::raw('EXTRACT(MONTH FROM created_at)::int'))
            ->orderBy('month')
            ->get()
            ->keyBy('month');

        $labels  = [];
        $revenue = [];
        $monthNames = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agt','Sep','Okt','Nov','Des'];

        for ($m = 1; $m <= 12; $m++) {
            $labels[]  = $monthNames[$m - 1];
            $revenue[] = $rows->has($m) ? (float) $rows[$m]->revenue : 0;
        }

        return compact('labels', 'revenue');
    }

    /**
     * Distribusi status pesanan dalam rentang tanggal.
     */
    public function getOrderStatusDistribution(?string $startDate = null, ?string $endDate = null): array
    {
        $start = $startDate ? Carbon::parse($startDate)->startOfDay() : Carbon::now()->startOfMonth();
        $end   = $endDate   ? Carbon::parse($endDate)->endOfDay()   : Carbon::now()->endOfDay();

        $rows = Order::select('status', DB::raw('COUNT(*) as count'))
            ->whereBetween('created_at', [$start, $end])
            ->groupBy('status')
            ->get()
            ->keyBy('status');

        $statuses = ['pending', 'processing', 'completed', 'cancelled'];
        $labels   = ['Pending', 'Diproses', 'Selesai', 'Dibatalkan'];
        $data     = [];

        foreach ($statuses as $s) {
            $data[] = $rows->has($s) ? (int) $rows[$s]->count : 0;
        }

        return compact('labels', 'data');
    }

    /**
     * Top N produk terlaris dalam rentang tanggal.
     */
    public function getTopProducts(?string $startDate = null, ?string $endDate = null, int $limit = 5): array
    {
        $start = $startDate ? Carbon::parse($startDate)->startOfDay() : Carbon::now()->startOfMonth();
        $end   = $endDate   ? Carbon::parse($endDate)->endOfDay()   : Carbon::now()->endOfDay();

        $rows = OrderItem::select('product_id', DB::raw('SUM(quantity) as total_qty'), DB::raw('SUM(subtotal) as total_revenue'))
            ->whereBetween('created_at', [$start, $end])
            ->groupBy('product_id')
            ->orderByDesc('total_qty')
            ->limit($limit)
            ->with('product:id,name')
            ->get();

        $labels  = [];
        $qty     = [];
        $revenue = [];

        foreach ($rows as $row) {
            $labels[]  = $row->product->name ?? 'Produk';
            $qty[]     = (int) $row->total_qty;
            $revenue[] = (float) $row->total_revenue;
        }

        return compact('labels', 'qty', 'revenue');
    }

    /**
     * Pertumbuhan pengguna baru per bulan dalam satu tahun.
     */
    public function getUserGrowth(int $year): array
    {
        $rows = User::whereYear('created_at', $year)
            ->select(
                DB::raw('EXTRACT(MONTH FROM created_at)::int AS month'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy(DB::raw('EXTRACT(MONTH FROM created_at)::int'))
            ->orderBy('month')
            ->get()
            ->keyBy('month');

        $labels = [];
        $counts = [];
        $monthNames = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agt','Sep','Okt','Nov','Des'];

        for ($m = 1; $m <= 12; $m++) {
            $labels[] = $monthNames[$m - 1];
            $counts[] = $rows->has($m) ? (int) $rows[$m]->count : 0;
        }

        return compact('labels', 'counts');
    }

    /**
     * Tabel ringkasan per periode (daily/weekly/monthly).
     */
    public function getPeriodSummary(?string $startDate, ?string $endDate, string $groupBy = 'daily'): array
    {
        $start = $startDate ? Carbon::parse($startDate)->startOfDay() : Carbon::now()->startOfMonth();
        $end   = $endDate   ? Carbon::parse($endDate)->endOfDay()   : Carbon::now()->endOfDay();

        $formatExpr = match($groupBy) {
            'weekly'  => "TO_CHAR(DATE_TRUNC('week', created_at), 'YYYY-\"W\"IW')",
            'monthly' => "TO_CHAR(created_at, 'YYYY-MM')",
            default   => "TO_CHAR(created_at, 'YYYY-MM-DD')",
        };

        $rows = Order::select(
                DB::raw("$formatExpr AS period"),
                DB::raw('COUNT(*) as total_orders'),
                DB::raw("SUM(CASE WHEN payment_status = 'paid' THEN total_amount ELSE 0 END) as total_revenue"),
                DB::raw("SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed"),
                DB::raw("SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END) as cancelled")
            )
            ->whereBetween('created_at', [$start, $end])
            ->groupBy('period')
            ->orderBy('period')
            ->get();

        return $rows->toArray();
    }

    /**
     * Ambil semua order dalam rentang tanggal untuk export.
     */
    public function getOrdersForExport(?string $startDate, ?string $endDate)
    {
        $start = $startDate ? Carbon::parse($startDate)->startOfDay() : Carbon::now()->startOfMonth();
        $end   = $endDate   ? Carbon::parse($endDate)->endOfDay()   : Carbon::now()->endOfDay();

        return Order::with(['buyer:id,name,email', 'store:id,name'])
            ->whereBetween('created_at', [$start, $end])
            ->orderByDesc('created_at')
            ->get();
    }
}
