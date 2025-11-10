<?php

namespace App\Http\Controllers;

use App\Models\PeminjamanBuku;
use App\Models\Denda;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PengembalianBukuController extends Controller
{
    public function index()
    {
        $peminjaman = PeminjamanBuku::with(['buku', 'user'])
            ->where('status', 'dipinjam')
            ->latest()
            ->get();

        return view('admin.pengembalian.index', compact('peminjaman'));
    }

    public function create(PeminjamanBuku $peminjaman)
    {
        return view('admin.pengembalian.create', compact('peminjaman'));
    }

    public function store(Request $request, PeminjamanBuku $peminjaman)
    {
        $request->validate([
            'tanggal_kembali' => 'required|date',
        ]);

        $tanggalKembaliSeharusnya = Carbon::parse($peminjaman->tanggal_kembali);
        $tanggalDikembalikan = Carbon::parse($request->tanggal_kembali);

        // Tanggal batas toleransi = tanggal kembali + 3 hari
        $tanggalBatasTanpaDenda = $tanggalKembaliSeharusnya->copy()->addDays(3);

        $totalDenda = 0;
        $hariTerlambat = 0;

        // Jika dikembalikan setelah masa toleransi
        if ($tanggalDikembalikan->gt($tanggalBatasTanpaDenda)) {
            $hariTerlambat = $tanggalBatasTanpaDenda->diffInDays($tanggalDikembalikan);
            $totalDenda = $hariTerlambat * 10000;

            // Simpan / perbarui denda di database
            Denda::updateOrCreate(
                ['peminjaman_id' => $peminjaman->id],
                [
                    'jumlah_hari' => $hariTerlambat,
                    'total_denda' => $totalDenda
                ]
            );
        }

        // Update status peminjaman jadi dikembalikan
        $peminjaman->update([
            'tanggal_kembali' => $request->tanggal_kembali,
            'status' => 'dikembalikan'
        ]);

        // Tambahkan stok buku kembali
        $peminjaman->buku->increment('stok', 1);

        return redirect()->route('pengembalian.index')
            ->with('success', 'Buku berhasil dikembalikan.' . 
                ($totalDenda > 0 ? ' Denda: Rp ' . number_format($totalDenda, 0, ',', '.') : ' Tanpa denda.'));
    }
}
