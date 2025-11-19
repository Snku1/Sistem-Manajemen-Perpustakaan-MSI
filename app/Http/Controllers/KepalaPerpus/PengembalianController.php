<?php

namespace App\Http\Controllers\KepalaPerpus;

use App\Http\Controllers\Controller;
use App\Models\PengembalianBuku;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PengembalianExport;

class PengembalianController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');

        $pengembalian = PengembalianBuku::with([
            'peminjaman.user',
            'peminjaman.buku',
            'peminjaman.denda' // Eager loading denda per peminjaman
        ])
            ->when($search, function ($query) use ($search) {
                $query->whereHas('peminjaman.user', function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%');
                })
                    ->orWhereHas('peminjaman.buku', function ($q) use ($search) {
                        $q->where('judul', 'like', '%' . $search . '%');
                    });
            })
            ->latest()
            ->paginate(10);

        return view('kepala-perpus.pengembalian.index', compact('pengembalian'));
    }

    public function exportPDF(Request $request)
    {
        $search = $request->get('search');

        $pengembalian = PengembalianBuku::with([
            'peminjaman.user',
            'peminjaman.buku',
            'peminjaman.denda'
        ])
            ->when($search, function ($query) use ($search) {
                $query->whereHas('peminjaman.user', function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%');
                })
                    ->orWhereHas('peminjaman.buku', function ($q) use ($search) {
                        $q->where('judul', 'like', '%' . $search . '%');
                    });
            })
            ->latest()
            ->get();

        $pdf = Pdf::loadView('kepala-perpus.pengembalian.export-pdf', compact('pengembalian'));
        return $pdf->download('data-pengembalian-' . date('Y-m-d') . '.pdf');
    }

    public function exportExcel(Request $request)
    {
        $search = $request->get('search');

        return Excel::download(new PengembalianExport($search), 'data-pengembalian-' . date('Y-m-d') . '.xlsx');
    }
}
