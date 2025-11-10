<?php

namespace App\Http\Controllers;

use App\Models\Denda;
use App\Models\PeminjamanBuku;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DendaController extends Controller
{
    public function index()
    {
        // Ambil semua data denda
        $denda = Denda::with(['peminjaman.buku', 'peminjaman.user'])->latest()->get();

        return view('admin.denda.index', compact('denda'));
    }

    public function hitungDenda()
    {
        // Ambil semua peminjaman yang sudah lewat tanggal kembali
        $peminjaman = PeminjamanBuku::where('status', 'dipinjam')->get();

        foreach ($peminjaman as $pinjam) {
            $tanggalKembali = Carbon::parse($pinjam->tanggal_kembali);
            $hariTerlambat = now()->diffInDays($tanggalKembali, false);

            if ($hariTerlambat > 0) {
                $totalDenda = $hariTerlambat * 10000;

                Denda::updateOrCreate(
                    ['peminjaman_id' => $pinjam->id],
                    ['jumlah_hari' => $hariTerlambat, 'total_denda' => $totalDenda]
                );
            }
        }

        return redirect()->route('denda.index')->with('success', 'Denda berhasil dihitung ulang!');
    }
}
