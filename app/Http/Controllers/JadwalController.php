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

        // Hitung statistik cepat dengan logika yang benar
        $sedangDipinjam = PeminjamanBuku::where('status', 'dipinjam')->count();

        $akanKembali = PeminjamanBuku::where('status', 'dipinjam')
            ->where('tanggal_kembali', '>=', today())
            ->count();

        $terlambat = PeminjamanBuku::where('status', 'dipinjam')
            ->where('tanggal_kembali', '<', today())
            ->count();

        // Hitung yang kena denda
        $kenaDenda = 0;
        if ($pengaturan) {
            $kenaDenda = PeminjamanBuku::where('status', 'dipinjam')
                ->where('tanggal_kembali', '<', today()->subDays($pengaturan->masa_tenggang))
                ->count();
        }

        // Data untuk kalender dengan integrasi aturan denda yang benar
        $events = PeminjamanBuku::with(['buku', 'user'])
            ->where('status', 'dipinjam')
            ->get()
            ->map(function ($peminjaman) use ($masaTenggang, $maksimalHari, $pengaturan) {
                $tanggalKembali = Carbon::parse($peminjaman->tanggal_kembali);
                $today = Carbon::today();

                // Tentukan warna berdasarkan status dan aturan denda yang BENAR
                if ($tanggalKembali->lt($today)) {
                    // Sudah lewat tanggal kembali - TERLAMBAT
                    $hariTerlambat = $today->diffInDays($tanggalKembali);
                    $masaTenggangEnd = $tanggalKembali->copy()->addDays($masaTenggang);

                    if ($today->lte($masaTenggangEnd)) {
                        $color = '#ffc107'; // Kuning - Dalam masa tenggang
                        $status = 'Masa Tenggang';
                        $hariMasaTenggang = $today->diffInDays($masaTenggangEnd);
                    } else {
                        $color = '#dc3545'; // Merah - Sudah kena denda
                        $status = 'Terlambat';
                        $hariKenaDenda = $today->diffInDays($masaTenggangEnd);
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

                // Hitung estimasi denda yang benar
                $estimasiDenda = $this->hitungEstimasiDenda($peminjaman, $pengaturan);

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
                        'estimasi_denda' => $estimasiDenda,
                        'hari_masa_tenggang' => isset($hariMasaTenggang) ? $hariMasaTenggang : 0,
                        'hari_kena_denda' => isset($hariKenaDenda) ? $hariKenaDenda : 0
                    ]
                ];
            });

        return view('admin.jadwal.index', compact(
            'events',
            'sedangDipinjam',
            'akanKembali',
            'terlambat',
            'kenaDenda',
            'pengaturan'
        ));
    }

    public function getHariIni()
    {
        $pengaturan = PengaturanDenda::getAktif();

        $peminjamanHariIni = PeminjamanBuku::with(['buku', 'user'])
            ->where(function ($query) {
                $query->whereDate('tanggal_pinjam', today())
                    ->orWhereDate('tanggal_kembali', today());
            })
            ->where('status', 'dipinjam')
            ->orderBy('tanggal_kembali')
            ->get()
            ->map(function ($peminjaman) use ($pengaturan) {
                $peminjaman->estimasi_denda = $this->hitungEstimasiDenda($peminjaman, $pengaturan);
                $peminjaman->status_detail = $this->getStatusDetail($peminjaman, $pengaturan);
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

                // PERBAIKAN: Hitung hari tersisa dengan benar
                $tanggalKembali = \Carbon\Carbon::parse($peminjaman->tanggal_kembali);
                $today = \Carbon\Carbon::today();

                // Hari tersisa adalah selisih antara tanggal kembali dan hari ini
                // Jika tanggal kembali = besok, maka hari_tersisa = 1
                $peminjaman->hari_tersisa = $today->diffInDays($tanggalKembali, false);

                $peminjaman->status_detail = $this->getStatusDetail($peminjaman, $pengaturan);
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
                $peminjaman->status_detail = $this->getStatusDetail($peminjaman, $pengaturan);
                return $peminjaman;
            });

        return view('admin.jadwal.terlambat', compact('terlambat', 'pengaturan'));
    }

    /**
     * Hitung estimasi denda berdasarkan aturan yang BENAR
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
        $hariKenaDenda = $masaTenggangEnd->diffInDays($today);
        if ($hariKenaDenda < 0) $hariKenaDenda = 0;

        return $hariKenaDenda * $pengaturan->denda_per_hari;
    }

    /**
     * Dapatkan detail status peminjaman
     */
    private function getStatusDetail($peminjaman, $pengaturan)
    {
        if (!$pengaturan) {
            return [
                'status' => 'tidak_ada_pengaturan',
                'hari' => 0,
                'denda' => 0
            ];
        }

        $tanggalKembali = Carbon::parse($peminjaman->tanggal_kembali);
        $today = Carbon::today();

        if ($tanggalKembali->gte($today)) {
            // Masih dalam waktu
            $hariTersisa = $today->diffInDays($tanggalKembali);
            return [
                'status' => 'dalam_waktu',
                'hari' => $hariTersisa,
                'denda' => 0
            ];
        }

        // Sudah lewat tanggal kembali
        $hariTerlambat = $today->diffInDays($tanggalKembali);
        $masaTenggangEnd = $tanggalKembali->copy()->addDays($pengaturan->masa_tenggang);

        if ($today->lte($masaTenggangEnd)) {
            // Dalam masa tenggang
            return [
                'status' => 'masa_tenggang',
                'hari' => $hariTerlambat,
                'denda' => 0,
                'sisa_masa_tenggang' => $today->diffInDays($masaTenggangEnd)
            ];
        } else {
            // Sudah kena denda
            $hariKenaDenda = $today->diffInDays($masaTenggangEnd);
            $totalDenda = $hariKenaDenda * $pengaturan->denda_per_hari;

            return [
                'status' => 'kena_denda',
                'hari' => $hariTerlambat,
                'denda' => $totalDenda,
                'hari_kena_denda' => $hariKenaDenda
            ];
        }
    }

// Tambahkan method ini di JadwalController
public function pustakawanIndex()
{
    // Statistik untuk pustakawan
    $sedangDipinjam = PeminjamanBuku::where('status', 'dipinjam')->count();
    $akanKembali = PeminjamanBuku::where('status', 'dipinjam')
        ->where('tanggal_kembali', '>=', today())
        ->count();
    $terlambat = PeminjamanBuku::where('status', 'dipinjam')
        ->where('tanggal_kembali', '<', today())
        ->count();

    $pengaturan = PengaturanDenda::getAktif();

    // Data untuk kalender dengan warna yang sama seperti admin
    $events = PeminjamanBuku::with(['buku', 'user'])
        ->where('status', 'dipinjam')
        ->get()
        ->map(function ($peminjaman) use ($pengaturan) {
            $tanggalKembali = Carbon::parse($peminjaman->tanggal_kembali);
            $today = Carbon::today();

            // Tentukan warna berdasarkan status (sama seperti admin)
            if ($tanggalKembali->lt($today)) {
                // Sudah lewat tanggal kembali - TERLAMBAT
                $masaTenggang = $pengaturan ? $pengaturan->masa_tenggang : 3;
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

            return [
                'title' => $peminjaman->buku->judul,
                'start' => $peminjaman->tanggal_pinjam,
                'end' => $tanggalKembali->copy()->addDay()->format('Y-m-d'),
                'color' => $color,
                'extendedProps' => [
                    'peminjaman_id' => $peminjaman->id,
                    'buku' => $peminjaman->buku->judul,
                    'peminjam' => $peminjaman->user->name,
                    'status' => $peminjaman->status,
                    'status_kalender' => $status,
                    'tanggal_pinjam' => $peminjaman->tanggal_pinjam,
                    'tanggal_kembali' => $peminjaman->tanggal_kembali,
                    'durasi_peminjaman' => Carbon::parse($peminjaman->tanggal_pinjam)->diffInDays($tanggalKembali),
                    'hari_terlambat' => $tanggalKembali->lt($today) ? $today->diffInDays($tanggalKembali) : 0,
                ]
            ];
        });

    return view('pustakawan.jadwal.index', compact(
        'events', 
        'sedangDipinjam', 
        'akanKembali', 
        'terlambat',
        'pengaturan'
    ));
}

public function pustakawanHariIni()
{
    $pengaturan = PengaturanDenda::getAktif();
    
    $peminjamanHariIni = PeminjamanBuku::with(['buku', 'user'])
        ->where(function ($query) {
            $query->whereDate('tanggal_pinjam', today())
                ->orWhereDate('tanggal_kembali', today());
        })
        ->where('status', 'dipinjam')
        ->orderBy('tanggal_kembali')
        ->get()
        ->map(function ($peminjaman) use ($pengaturan) {
            $peminjaman->estimasi_denda = $this->hitungEstimasiDenda($peminjaman, $pengaturan);
            $peminjaman->status_detail = $this->getStatusDetail($peminjaman, $pengaturan);
            return $peminjaman;
        });

    return view('pustakawan.jadwal.hari-ini', compact('peminjamanHariIni', 'pengaturan'));
}

public function pustakawanAkanDatang()
{
    $pengaturan = PengaturanDenda::getAktif();
    
    $peminjamanAkanDatang = PeminjamanBuku::with(['buku', 'user'])
        ->where('status', 'dipinjam')
        ->where('tanggal_kembali', '>=', today())
        ->orderBy('tanggal_kembali')
        ->get()
        ->map(function ($peminjaman) use ($pengaturan) {
            $peminjaman->estimasi_denda = $this->hitungEstimasiDenda($peminjaman, $pengaturan);
            
            // Hitung hari tersisa dengan benar
            $tanggalKembali = \Carbon\Carbon::parse($peminjaman->tanggal_kembali);
            $today = \Carbon\Carbon::today();
            $peminjaman->hari_tersisa = $today->diffInDays($tanggalKembali, false);
            
            $peminjaman->status_detail = $this->getStatusDetail($peminjaman, $pengaturan);
            return $peminjaman;
        });

    return view('pustakawan.jadwal.akan-datang', compact('peminjamanAkanDatang', 'pengaturan'));
}

public function pustakawanTerlambat()
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
            $peminjaman->status_detail = $this->getStatusDetail($peminjaman, $pengaturan);
            return $peminjaman;
        });

    return view('pustakawan.jadwal.terlambat', compact('terlambat', 'pengaturan'));
}
}
