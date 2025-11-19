<?php

namespace App\Http\Controllers\KepalaPerpus;

use App\Http\Controllers\Controller;
use App\Models\PeminjamanBuku;
use App\Models\PengembalianBuku;
use App\Models\Denda;
use App\Models\Buku;
use App\Models\User;

class DashboardKepalaPerpusController extends Controller
{
    public function index()
    {
        $totalPeminjaman = PeminjamanBuku::count();
        $totalPengembalian = PengembalianBuku::count();
        $totalDenda = Denda::sum('total_denda');
        $totalBuku = Buku::count();
        $totalUsers = User::where('role', 'pustakawan')
            ->Count();

        // Buku paling populer (sama seperti buku paling sering dipinjam)
        $bukuPopuler = Buku::withCount('peminjaman')
            ->orderBy('peminjaman_count', 'DESC')
            ->first();

        // Pustakawan paling aktif
        $pustakawanAktif = User::where('role', 'pustakawan')
            ->withCount('peminjaman_buku')
            ->orderBy('peminjaman_buku_count', 'DESC')
            ->first();

        return view('kepala-perpus.dashboard', compact(
            'totalPeminjaman',
            'totalPengembalian',
            'totalDenda',
            'totalBuku',
            'totalUsers',
            'bukuPopuler',
            'pustakawanAktif'
        ));
    }
}
