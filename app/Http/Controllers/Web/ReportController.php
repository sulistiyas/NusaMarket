<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\ReportService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    protected ReportService $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    /**
     * Halaman utama laporan.
     */
    public function index(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate   = $request->get('end_date',   Carbon::now()->format('Y-m-d'));
        $year      = $request->get('year',        Carbon::now()->year);

        $summary = $this->reportService->getSummaryStats($startDate, $endDate);

        return view('pages.reports.index', compact('summary', 'startDate', 'endDate', 'year'));
    }

    /**
     * Export laporan ke PDF atau Excel.
     */
    public function export(Request $request, string $type)
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate   = $request->get('end_date',   Carbon::now()->format('Y-m-d'));

        $orders  = $this->reportService->getOrdersForExport($startDate, $endDate);
        $summary = $this->reportService->getSummaryStats($startDate, $endDate);

        if ($type === 'pdf') {
            $pdf = app('dompdf.wrapper')
                ->loadView('pages.reports.export-pdf', compact('orders', 'summary', 'startDate', 'endDate'))
                ->setPaper('a4', 'landscape');

            return $pdf->download("laporan-{$startDate}-to-{$endDate}.pdf");
        }

        if ($type === 'excel') {
            return $this->exportCsv($orders, $startDate, $endDate);
        }

        abort(404, 'Tipe export tidak dikenali.');
    }

    /**
     * Export data ke CSV (fallback jika maatwebsite/excel belum tersedia).
     */
    private function exportCsv($orders, string $startDate, string $endDate)
    {
        $filename = "laporan-{$startDate}-to-{$endDate}.csv";
        $headers  = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($orders) {
            $file = fopen('php://output', 'w');
            // BOM untuk Excel agar baca UTF-8 dengan benar
            fputs($file, "\xEF\xBB\xBF");
            fputcsv($file, ['No. Pesanan', 'Pembeli', 'Email', 'Toko', 'Total', 'Status', 'Pembayaran', 'Tanggal']);

            foreach ($orders as $order) {
                fputcsv($file, [
                    $order->order_number,
                    $order->buyer->name ?? '-',
                    $order->buyer->email ?? '-',
                    $order->store->name ?? '-',
                    number_format($order->total_amount, 0, ',', '.'),
                    strtoupper($order->status),
                    strtoupper($order->payment_status),
                    $order->created_at->format('d/m/Y H:i'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
