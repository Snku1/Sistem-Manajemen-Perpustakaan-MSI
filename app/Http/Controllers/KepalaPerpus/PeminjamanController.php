<?php

namespace App\Http\Controllers\KepalaPerpus;

use App\Http\Controllers\Controller;
use App\Models\PeminjamanBuku;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PeminjamanExport;

class PeminjamanController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        
        $peminjaman = PeminjamanBuku::with(['buku', 'user'])
            ->when($search, function($query) use ($search) {
                $query->whereHas('user', function($q) use ($search) {
                    $q->where('name', 'like', '%'.$search.'%');
                })
                ->orWhereHas('buku', function($q) use ($search) {
                    $q->where('judul', 'like', '%'.$search.'%');
                });
            })
            ->latest()
            ->paginate(10);

        return view('kepala-perpus.peminjaman.index', compact('peminjaman'));
    }

    public function show($id)
    {
        $data = PeminjamanBuku::with(['buku', 'user'])
            ->findOrFail($id);

        return view('kepala-perpus.peminjaman.show', compact('data'));
    }

    public function exportPDF(Request $request)
    {
        $search = $request->get('search');
        
        $peminjaman = PeminjamanBuku::with(['buku', 'user'])
            ->when($search, function($query) use ($search) {
                $query->whereHas('user', function($q) use ($search) {
                    $q->where('name', 'like', '%'.$search.'%');
                })
                ->orWhereHas('buku', function($q) use ($search) {
                    $q->where('judul', 'like', '%'.$search.'%');
                });
            })
            ->latest()
            ->get();

        $pdf = Pdf::loadView('kepala-perpus.peminjaman.export-pdf', compact('peminjaman'));
        return $pdf->download('data-peminjaman-' . date('Y-m-d') . '.pdf');
    }

    public function exportExcel(Request $request)
    {
        $search = $request->get('search');
        
        return Excel::download(new PeminjamanExport($search), 'data-peminjaman-' . date('Y-m-d') . '.xlsx');
    }
}