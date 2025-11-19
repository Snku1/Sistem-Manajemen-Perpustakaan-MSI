<?php

namespace App\Http\Controllers\KepalaPerpus;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Denda;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\DendaExport;
use Maatwebsite\Excel\Facades\Excel;

class DendaController extends Controller
{
    public function index()
    {
        // Total semua denda
        $totalDenda = Denda::sum('total_denda');

        // Pustakawan dengan total denda terbanyak
        $pustakawanTerbanyak = User::where('role', 'pustakawan')
            ->withSum('denda', 'total_denda')
            ->orderBy('denda_sum_total_denda', 'desc')
            ->first();

        // Detail denda per pustakawan
        $detailDenda = User::where('role', 'pustakawan')
            ->withCount('denda')
            ->withSum('denda', 'total_denda')
            ->withAvg('denda', 'total_denda')
            ->withMax('denda', 'total_denda')
            ->withMin('denda', 'total_denda')
            ->get();

        return view('kepala-perpus.denda.index', compact(
            'totalDenda',
            'pustakawanTerbanyak',
            'detailDenda'
        ));
    }

    public function exportPDF()
    {
        // Total semua denda
        $totalDenda = Denda::sum('total_denda');

        // Pustakawan dengan total denda terbanyak
        $pustakawanTerbanyak = User::where('role', 'pustakawan')
            ->withSum('denda', 'total_denda')
            ->orderBy('denda_sum_total_denda', 'desc')
            ->first();

        // Detail denda per pustakawan
        $detailDenda = User::where('role', 'pustakawan')
            ->withCount('denda')
            ->withSum('denda', 'total_denda')
            ->withAvg('denda', 'total_denda')
            ->withMax('denda', 'total_denda')
            ->withMin('denda', 'total_denda')
            ->get();

        $pdf = Pdf::loadView('kepala-perpus.denda.export-pdf', compact(
            'totalDenda',
            'pustakawanTerbanyak',
            'detailDenda'
        ));

        return $pdf->download('laporan-denda-' . date('Y-m-d') . '.pdf');
    }

    public function exportExcel()
    {
        return Excel::download(new DendaExport, 'laporan-denda-' . date('Y-m-d') . '.xlsx');
    }
}