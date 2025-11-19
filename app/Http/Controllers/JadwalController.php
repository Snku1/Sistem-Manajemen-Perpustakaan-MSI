<?php

namespace App\Http\Controllers;

use App\Models\PeminjamanBuku;
use App\Models\Buku;
use App\Models\User;
use App\Models\PengaturanDenda;
use Illuminate\Http\Request;
use Carbon\Carbon;

class JadwalController extends Controller
{
    public function index()
    {
        // Ambil pengaturan denda aktif
        $pengaturan = PengaturanDenda::getAktif();
        $maksimalHari = $pengaturan ? $pengaturan->maksimal_hari_peminjaman : 7;
        $masaTenggang = $pengaturan ? $pengaturan->masa_tenggang : 3;

        // Hitung statistik cepat
        $sedangDipinjam = PeminjamanBuku::where('status', 'dipinjam')->count();
        $akanKembali = PeminjamanBuku::where('status', 'dipinjam')
            ->where('tanggal_kembali', '>=', today())
            ->count();
        $terlambat = PeminjamanBuku::where('status', 'dipinjam')
            ->where('tanggal_kembali', '<', today())
            ->count();

        // Data untuk kalender dengan integrasi aturan denda
        $events = PeminjamanBuku::with(['buku', 'user'])
            ->where('status', 'dipinjam')
            ->get()
            ->map(function ($peminjaman) use ($masaTenggang, $maksimalHari, $pengaturan) {
                $tanggalKembali = Carbon::parse($peminjaman->tanggal_kembali);
                $today = Carbon::today();
                
                // Tentukan warna berdasarkan status dan aturan denda
                if ($tanggalKembali->lt($today)) {
                    // Sudah lewat tanggal kembali - TERLAMBAT
                    $hariTerlambat = $today->diffInDays($tanggalKembali);
                    $masaTenggangEnd = $tanggalKembali->copy()->addDays($masaTenggang);
                    
                    if ($today->lte($masaTenggangEnd)) {
                        $color = '#ffc107'; // Kuning - Dalam masa tenggang
                        $status = 'Masa Tenggang';
                    } else {
                        $color = '#dc3545'; // Merah - Sudah kena denda
                        $status = 'Terlambat';
                    }
                } elseif ($tanggalKembali->eq($today)) {
                    $color = '#fd7e14'; // Orange - Hari ini batas kembali
                    $status = 'Batas Kembali';
                } else {
                    $color = '#28a745'; // Hijau - Masih dalam waktu
                    $status = 'Aktif';
                }
                
                // Hitung durasi peminjaman
                $tanggalPinjam = Carbon::parse($peminjaman->tanggal_pinjam);
                $durasi = $tanggalPinjam->diffInDays($tanggalKembali);
                
                return [
                    'title' => $peminjaman->buku->judul,
                    'start' => $peminjaman->tanggal_pinjam,
                    'end' => $tanggalKembali->copy()->addDay()->format('Y-m-d'), // +1 day untuk fullcalendar
                    'color' => $color,
                    'extendedProps' => [
                        'peminjaman_id' => $peminjaman->id,
                        'buku' => $peminjaman->buku->judul,
                        'peminjam' => $peminjaman->user->name,
                        'status' => $peminjaman->status,
                        'status_kalender' => $status,
                        'tanggal_pinjam' => $peminjaman->tanggal_pinjam,
                        'tanggal_kembali' => $peminjaman->tanggal_kembali,
                        'durasi_peminjaman' => $durasi,
                        'maksimal_hari' => $maksimalHari,
                        'masa_tenggang' => $masaTenggang,
                        'hari_terlambat' => $tanggalKembali->lt($today) ? $today->diffInDays($tanggalKembali) : 0,
                        'estimasi_denda' => $this->hitungEstimasiDenda($peminjaman, $pengaturan)
                    ]
                ];
            });

        return view('admin.jadwal.index', compact(
            'events', 
            'sedangDipinjam', 
            'akanKembali', 
            'terlambat',
            'pengaturan'
        ));
    }

    public function getHariIni()
    {
        $pengaturan = PengaturanDenda::getAktif();
        
        $peminjamanHariIni = PeminjamanBuku::with(['buku', 'user'])
            ->where(function($query) {
                $query->whereDate('tanggal_pinjam', today())
                      ->orWhereDate('tanggal_kembali', today());
            })
            ->where('status', 'dipinjam')
            ->orderBy('tanggal_kembali')
            ->get()
            ->map(function ($peminjaman) use ($pengaturan) {
                $peminjaman->estimasi_denda = $this->hitungEstimasiDenda($peminjaman, $pengaturan);
                return $peminjaman;
            });

        return view('admin.jadwal.hari-ini', compact('peminjamanHariIni', 'pengaturan'));
    }

    public function getAkanDatang()
    {
        $pengaturan = PengaturanDenda::getAktif();
        
        $peminjamanAkanDatang = PeminjamanBuku::with(['buku', 'user'])
            ->where('status', 'dipinjam')
            ->where('tanggal_kembali', '>=', today())
            ->orderBy('tanggal_kembali')
            ->get()
            ->map(function ($peminjaman) use ($pengaturan) {
                $peminjaman->estimasi_denda = $this->hitungEstimasiDenda($peminjaman, $pengaturan);
                $peminjaman->hari_tersisa = Carbon::parse($peminjaman->tanggal_kembali)->diffInDays(today());
                return $peminjaman;
            });

        return view('admin.jadwal.akan-datang', compact('peminjamanAkanDatang', 'pengaturan'));
    }

    public function getTerlambat()
    {
        $pengaturan = PengaturanDenda::getAktif();
        
        $terlambat = PeminjamanBuku::with(['buku', 'user'])
            ->where('status', 'dipinjam')
            ->where('tanggal_kembali', '<', today())
            ->orderBy('tanggal_kembali')
            ->get()
            ->map(function ($peminjaman) use ($pengaturan) {
                $peminjaman->estimasi_denda = $this->hitungEstimasiDenda($peminjaman, $pengaturan);
                $peminjaman->hari_terlambat = Carbon::parse($peminjaman->tanggal_kembali)->diffInDays(today());
                return $peminjaman;
            });

        return view('admin.jadwal.terlambat', compact('terlambat', 'pengaturan'));
    }

    /**
     * Hitung estimasi denda berdasarkan aturan
     */
    private function hitungEstimasiDenda($peminjaman, $pengaturan)
    {
        if (!$pengaturan) return 0;

        $tanggalKembali = Carbon::parse($peminjaman->tanggal_kembali);
        $today = Carbon::today();
        
        // Jika belum lewat tanggal kembali, tidak ada denda
        if ($tanggalKembali->gte($today)) {
            return 0;
        }

        // Hitung hari terlambat setelah masa tenggang
        $masaTenggangEnd = $tanggalKembali->copy()->addDays($pengaturan->masa_tenggang);
        
        if ($today->lte($masaTenggangEnd)) {
            return 0; // Masih dalam masa tenggang
        }

        // Sudah lewat masa tenggang, hitung denda
        $hariKenaDenda = $today->diffInDays($masaTenggangEnd);
        return $hariKenaDenda * $pengaturan->denda_per_hari;
    }
}