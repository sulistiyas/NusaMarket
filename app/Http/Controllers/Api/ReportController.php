<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ReportService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    use ApiResponse;

    protected ReportService $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    /**
     * Data chart revenue per bulan.
     */
    public function chartRevenue(Request $request)
    {
        $year = (int) $request->get('year', Carbon::now()->year);
        $data = $this->reportService->getMonthlyRevenue($year);
        return $this->success($data, 'Data chart revenue berhasil diambil.');
    }

    /**
     * Data chart distribusi status pesanan.
     */
    public function chartStatus(Request $request)
    {
        $data = $this->reportService->getOrderStatusDistribution(
            $request->get('start_date'),
            $request->get('end_date')
        );
        return $this->success($data, 'Data distribusi status pesanan berhasil diambil.');
    }

    /**
     * Data chart top 5 produk terlaris.
     */
    public function chartTopProducts(Request $request)
    {
        $data = $this->reportService->getTopProducts(
            $request->get('start_date'),
            $request->get('end_date'),
            5
        );
        return $this->success($data, 'Data top produk berhasil diambil.');
    }

    /**
     * Data chart pertumbuhan pengguna baru.
     */
    public function chartUserGrowth(Request $request)
    {
        $year = (int) $request->get('year', Carbon::now()->year);
        $data = $this->reportService->getUserGrowth($year);
        return $this->success($data, 'Data pertumbuhan pengguna berhasil diambil.');
    }

    /**
     * Data tabel ringkasan per periode.
     */
    public function tableSummary(Request $request)
    {
        $data = $this->reportService->getPeriodSummary(
            $request->get('start_date'),
            $request->get('end_date'),
            $request->get('group_by', 'daily')
        );
        return $this->success($data, 'Data ringkasan periode berhasil diambil.');
    }
}
