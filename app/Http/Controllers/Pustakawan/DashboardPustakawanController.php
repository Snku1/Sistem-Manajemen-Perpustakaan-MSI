<?php

namespace App\Http\Controllers\Pustakawan;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\PeminjamanBuku;
use App\Models\PengembalianBuku;

class DashboardPustakawanController extends Controller
{
    public function index()
    {
        // Hitung data untuk statistik
        $totalBuku = Buku::count();
        $totalPeminjaman = PeminjamanBuku::count();
        $totalPengembalian = PengembalianBuku::count();

        return view('pustakawan.dashboard', compact(
            'totalBuku',
            'totalPeminjaman',
            'totalPengembalian'
        ));
    }
}
