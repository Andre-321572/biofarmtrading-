<?php

namespace App\Http\Controllers\Rp;

use App\Http\Controllers\Controller;
use App\Models\ProductionReport;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        // Key stats for current month
        $reportsCount = ProductionReport::whereBetween('date_rapport', [$startOfMonth, $endOfMonth])->count();
        
        $monthlyReports = ProductionReport::with('lots')
            ->whereBetween('date_rapport', [$startOfMonth, $endOfMonth])
            ->get();

        $totalDeclayeMonth = $monthlyReports->sum(fn($r) => $r->total_declaye);
        $totalSachetsMonth = $monthlyReports->sum(fn($r) => $r->total_sachets);
        $totalDechetsMonth = $monthlyReports->sum(fn($r) => $r->total_dechets);
        $totalTtpmMonth = $monthlyReports->sum(fn($r) => $r->total_ttpm);
        $totalPaMonth = $monthlyReports->sum(fn($r) => $r->total_pa);
        $avgOuvriersMonth = round($monthlyReports->avg('nombre_ouvriers') ?? 0, 1);

        $dechetsPercentage = $totalDeclayeMonth > 0 ? round(($totalDechetsMonth / $totalDeclayeMonth) * 100, 1) : 0;
        $totalWorkerDays = $monthlyReports->sum('nombre_ouvriers');
        $productivityPerWorker = $totalWorkerDays > 0 ? round($totalDeclayeMonth / $totalWorkerDays, 1) : 0;

        // Cut breakdowns
        $cutStats = [
            'rcl_2kg' => $monthlyReports->sum(fn($r) => $r->total_sachets_rcl_2kg),
            'rcl_1kg' => $monthlyReports->sum(fn($r) => $r->total_sachets_rcl_1kg),
            'mcl_2kg' => $monthlyReports->sum(fn($r) => $r->total_sachets_mcl_2kg),
            'mcl_1kg' => $monthlyReports->sum(fn($r) => $r->total_sachets_mcl_1kg),
            'rps_2kg' => $monthlyReports->sum(fn($r) => $r->total_sachets_rps_2kg),
            'rps_1kg' => $monthlyReports->sum(fn($r) => $r->total_sachets_rps_1kg),
            'mps_2kg' => $monthlyReports->sum(fn($r) => $r->total_sachets_mps_2kg),
            'mps_1kg' => $monthlyReports->sum(fn($r) => $r->total_sachets_mps_1kg),
            'lpa_2kg' => $monthlyReports->sum(fn($r) => $r->total_sachets_lpa_2kg),
            'lpa_1kg' => $monthlyReports->sum(fn($r) => $r->total_sachets_lpa_1kg),
            'lpa_100g' => $monthlyReports->sum(fn($r) => $r->total_sachets_lpa_100g),
        ];

        $recentReports = ProductionReport::with('lots', 'user')
            ->orderBy('date_rapport', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('rp.dashboard', compact(
            'reportsCount',
            'totalDeclayeMonth',
            'totalSachetsMonth',
            'totalDechetsMonth',
            'totalTtpmMonth',
            'totalPaMonth',
            'avgOuvriersMonth',
            'dechetsPercentage',
            'productivityPerWorker',
            'cutStats',
            'recentReports'
        ));
    }
}
