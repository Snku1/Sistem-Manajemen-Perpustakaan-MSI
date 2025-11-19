<?php

namespace App\Http\Controllers;

use App\Models\PeminjamanBuku;
use App\Models\PengembalianBuku;
use App\Models\Denda;
use App\Models\PengaturanDenda;
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

    // Di PengembalianController.php - method store()
    public function store(Request $request, PeminjamanBuku $peminjaman)
    {
        $request->validate([
            'tanggal_kembali' => 'required|date',
        ]);

        // Ambil pengaturan denda aktif
        $pengaturan = PengaturanDenda::getAktif();

        if (!$pengaturan) {
            return redirect()->back()->with('error', 'Pengaturan denda belum ditentukan.');
        }

        $batasKembali = Carbon::parse($peminjaman->tanggal_kembali); // Batas waktu dari peminjaman
        $tanggalDikembalikan = Carbon::parse($request->tanggal_kembali); // Tanggal aktual pengembalian

        // Tanggal batas toleransi = batas kembali + masa tenggang
        $tanggalBatasTanpaDenda = $batasKembali->copy()->addDays($pengaturan->masa_tenggang);

        $totalDenda = 0;
        $hariTerlambat = 0;

        // Jika dikembalikan setelah masa toleransi
        if ($tanggalDikembalikan->gt($tanggalBatasTanpaDenda)) {
            $hariTerlambat = $tanggalBatasTanpaDenda->diffInDays($tanggalDikembalikan);
            $totalDenda = $hariTerlambat * $pengaturan->denda_per_hari;

            // Simpan denda
            Denda::create([
                'peminjaman_id' => $peminjaman->id,
                'user_id' => $peminjaman->user_id,
                'jumlah_hari' => $hariTerlambat,
                'total_denda' => $totalDenda
            ]);
        }

        // BUAT data pengembalian dengan TANGGAL DIKEMBALIKAN yang sebenarnya
        PengembalianBuku::create([
            'peminjaman_id' => $peminjaman->id,
            'tanggal_kembali' => $tanggalDikembalikan, // Tanggal aktual pengembalian
        ]);

        // Update status peminjaman jadi dikembalikan
        $peminjaman->update([
            'status' => 'dikembalikan'
        ]);

        // Tambahkan stok buku
        $peminjaman->buku->increment('stok', 1);

        return redirect()->route('pengembalian.index')
            ->with('success', 'Buku berhasil dikembalikan.' .
                ($totalDenda > 0 ? ' Denda: Rp ' . number_format($totalDenda, 0, ',', '.') : ' Tanpa denda.'));
    }
}
