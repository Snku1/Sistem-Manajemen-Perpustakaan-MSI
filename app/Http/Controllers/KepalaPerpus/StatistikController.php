<?php

namespace App\Http\Controllers\KepalaPerpus;

use App\Http\Controllers\Controller;
use App\Models\PeminjamanBuku;
use App\Models\PengembalianBuku;
use App\Models\Buku;
use Illuminate\Support\Facades\DB;

class StatistikController extends Controller
{
    public function index()
    {
        // Total peminjaman & pengembalian
        $totalPeminjaman = PeminjamanBuku::count();
        $totalPengembalian = PengembalianBuku::count();

        // Buku terbanyak & tersedikit
        $bukuTerbanyak = Buku::withCount('peminjaman')->orderBy('peminjaman_count', 'desc')->first();
        $bukuTersedikit = Buku::withCount('peminjaman')->orderBy('peminjaman_count', 'asc')->first();

        // Grafik peminjaman 12 bulan terakhir
        $chartPeminjaman = PeminjamanBuku::select(
            DB::raw('MONTH(created_at) as bulan'),
            DB::raw('COUNT(*) as total')
        )
            ->whereYear('created_at', date('Y'))
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->pluck('total', 'bulan');

        // Grafik pengembalian 12 bulan terakhir
        $chartPengembalian = PengembalianBuku::select(
            DB::raw('MONTH(created_at) as bulan'),
            DB::raw('COUNT(*) as total')
        )
            ->whereYear('created_at', date('Y'))
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->pluck('total', 'bulan');

        // Isi bulan yang tidak ada data dengan 0 untuk peminjaman
        $chartPeminjamanComplete = [];
        for ($i = 1; $i <= 12; $i++) {
            $chartPeminjamanComplete[$i] = $chartPeminjaman[$i] ?? 0;
        }
        $chartPeminjaman = collect($chartPeminjamanComplete);

        // Isi bulan yang tidak ada data dengan 0 untuk pengembalian
        $chartPengembalianComplete = [];
        for ($i = 1; $i <= 12; $i++) {
            $chartPengembalianComplete[$i] = $chartPengembalian[$i] ?? 0;
        }
        $chartPengembalian = collect($chartPengembalianComplete);
        
        // Total peminjaman & pengembalian tahun ini
        $totalPeminjamanTahunIni = array_sum($chartPeminjaman->toArray());
        $totalPengembalianTahunIni = array_sum($chartPengembalian->toArray());

        // Top 10 buku paling Banyak dipinjam
        $top10Banyak = Buku::withCount('peminjaman')
            ->orderBy('peminjaman_count', 'desc')
            ->take(10)
            ->get();

        // Top 5 buku paling banyak dipinjam untuk grafik
        $top5Banyak = Buku::withCount('peminjaman')
            ->orderBy('peminjaman_count', 'desc')
            ->take(5)
            ->get();

        // Top 10 buku paling sedikit dipinjam
        $top10Sedikit = Buku::withCount('peminjaman')
            ->orderBy('peminjaman_count', 'asc')
            ->take(10)
            ->get();

        return view('kepala-perpus.statistik.index', compact(
            'totalPeminjaman',
            'totalPengembalian',
            'bukuTerbanyak',
            'bukuTersedikit',
            'chartPeminjaman',
            'chartPengembalian',
            'totalPeminjamanTahunIni',
            'totalPengembalianTahunIni',
            'top10Banyak',
            'top5Banyak',
            'top10Sedikit'
        ));
    }
}