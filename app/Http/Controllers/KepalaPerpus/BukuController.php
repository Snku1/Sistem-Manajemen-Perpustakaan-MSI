<?php

namespace App\Http\Controllers\KepalaPerpus;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BukuExport;

class BukuController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        
        $buku = Buku::withCount([
            'peminjaman as dipinjam' => function($q){
                $q->where('status', 'dipinjam');
            }
        ])
        ->withCount([
            'peminjaman as dikembalikan' => function($q){
                $q->where('status', 'dikembalikan');
            }
        ])
        ->when($search, function($query) use ($search) {
            $query->where('judul', 'like', '%'.$search.'%');
        })
        ->paginate(10);

        return view('kepala-perpus.buku.index', compact('buku'));
    }

    public function exportPDF()
    {
        $buku = Buku::withCount([
            'peminjaman as dipinjam' => function($q){
                $q->where('status', 'dipinjam');
            }
        ])
        ->withCount([
            'peminjaman as dikembalikan' => function($q){
                $q->where('status', 'dikembalikan');
            }
        ])->get();

        $pdf = Pdf::loadView('kepala-perpus.buku.export-pdf', compact('buku'));
        return $pdf->download('laporan-stok-buku-' . date('Y-m-d') . '.pdf');
    }

    public function exportExcel()
    {
        return Excel::download(new BukuExport, 'laporan-stok-buku-' . date('Y-m-d') . '.xlsx');
    }
}