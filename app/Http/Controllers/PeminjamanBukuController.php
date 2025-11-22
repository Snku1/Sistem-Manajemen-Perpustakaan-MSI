<?php

namespace App\Http\Controllers;

use App\Models\PeminjamanBuku;
use App\Models\Buku;
use App\Models\User;
use App\Models\Denda;
use App\Models\PengaturanDenda;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PeminjamanBukuController extends Controller
{
    public function index()
    {
        $peminjaman = PeminjamanBuku::with(['buku', 'user'])->latest()->get();
        return view('admin.peminjaman.index', compact('peminjaman'));
    }

    public function create()
    {
        $buku = Buku::where('stok', '>', 0)->get(); // Hanya buku yang ada stok
        $users = User::whereIn('role', ['pustakawan', 'admin'])->get(); // Hanya user dengan role tertentu

        // Ambil pengaturan batas waktu untuk info
        $pengaturan = PengaturanDenda::getAktif();
        $batasHari = $pengaturan ? $pengaturan->batas_waktu_peminjaman : 7;

        return view('admin.peminjaman.create', compact('buku', 'users', 'batasHari'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'buku_id' => 'required|exists:buku,id',
            'user_id' => 'required|exists:users,id',
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali' => 'required|date|after_or_equal:tanggal_pinjam', // Wajib diisi user
        ]);

        // Ambil buku yang dipinjam
        $buku = Buku::findOrFail($request->buku_id);

        // Cek apakah stok cukup
        if ($buku->stok <= 0) {
            return redirect()->back()->with('error', 'Stok buku "' . $buku->judul . '" sudah habis!');
        }

        // Ambil pengaturan maksimal hari peminjaman
        $pengaturan = PengaturanDenda::getAktif();
        $maksimalHari = $pengaturan ? $pengaturan->maksimal_hari_peminjaman : 7;

        // Validasi: Tanggal kembali tidak boleh melebihi maksimal hari
        $tanggalPinjam = Carbon::parse($request->tanggal_pinjam);
        $tanggalKembali = Carbon::parse($request->tanggal_kembali);
        $selisihHari = $tanggalPinjam->diffInDays($tanggalKembali);

        if ($selisihHari > $maksimalHari) {
            return redirect()->back()->with(
                'error',
                "Tanggal kembali tidak boleh melebihi {$maksimalHari} hari dari tanggal pinjam. " .
                    "Anda memilih {$selisihHari} hari."
            );
        }

        // Validasi: Tanggal kembali harus setelah tanggal pinjam
        if ($tanggalKembali->lte($tanggalPinjam)) {
            return redirect()->back()->with('error', 'Tanggal kembali harus setelah tanggal pinjam!');
        }

        // Simpan peminjaman dengan tanggal kembali yang dipilih user
        $peminjaman = PeminjamanBuku::create([
            'buku_id' => $request->buku_id,
            'user_id' => $request->user_id,
            'tanggal_pinjam' => $tanggalPinjam,
            'tanggal_kembali' => $tanggalKembali, // Tanggal yang dipilih user
            'status' => 'dipinjam'
        ]);

        // Jangan buat data pengembalian sekarang
        // Data pengembalian akan dibuat saat buku benar-benar dikembalikan

        // Kurangi stok buku
        $buku->decrement('stok', 1);

        $message = "Peminjaman berhasil ditambahkan. ";
        $message .= "Durasi pinjam: {$selisihHari} hari (maksimal: {$maksimalHari} hari).";

        // Setelah peminjaman berhasil dibuat, kirim email konfirmasi
        if ($peminjaman) {
            // Kirim email konfirmasi peminjaman baru
            (new ReminderController)->kirimEmailPeminjamanBaru($peminjaman->id);
        }

        return redirect()->route('peminjaman.index')->with('success', $message);
    }

    public function edit($id)
    {
        $peminjaman = PeminjamanBuku::findOrFail($id);

        // Cek apakah buku sudah dikembalikan
        if ($peminjaman->status === 'dikembalikan') {
            return redirect()->route('peminjaman.index')->with('error', 'Tidak dapat mengedit peminjaman yang sudah dikembalikan.');
        }

        $buku = Buku::where('stok', '>', 0)
            ->orWhere('id', $peminjaman->buku_id) // Tetap tampilkan buku yang sedang dipinjam
            ->get();

        $users = User::whereIn('role', ['pustakawan', 'admin'])->get();

        // Ambil pengaturan untuk info
        $pengaturan = PengaturanDenda::getAktif();
        $batasHari = $pengaturan ? $pengaturan->batas_waktu_peminjaman : 7;

        return view('admin.peminjaman.edit', compact('peminjaman', 'buku', 'users', 'batasHari'));
    }

    public function update(Request $request, $id)
    {
        $peminjaman = PeminjamanBuku::findOrFail($id);

        // Cek apakah buku sudah dikembalikan
        if ($peminjaman->status === 'dikembalikan') {
            return redirect()->route('peminjaman.index')->with('error', 'Tidak dapat mengupdate peminjaman yang sudah dikembalikan.');
        }

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'buku_id' => 'required|exists:buku,id',
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali' => 'required|date|after_or_equal:tanggal_pinjam',
        ]);

        // Ambil buku lama dan baru untuk update stok
        $bukuLama = Buku::find($peminjaman->buku_id);
        $bukuBaru = Buku::find($request->buku_id);

        // Jika buku berubah, update stok
        if ($peminjaman->buku_id != $request->buku_id) {
            // Kembalikan stok buku lama
            if ($bukuLama) {
                $bukuLama->increment('stok', 1);
            }

            // Kurangi stok buku baru
            if ($bukuBaru && $bukuBaru->stok > 0) {
                $bukuBaru->decrement('stok', 1);
            } else {
                return redirect()->back()->with('error', 'Stok buku "' . $bukuBaru->judul . '" tidak mencukupi!');
            }
        }

        // Update peminjaman
        $peminjaman->update([
            'user_id' => $request->user_id,
            'buku_id' => $request->buku_id,
            'tanggal_pinjam' => $request->tanggal_pinjam,
            'tanggal_kembali' => $request->tanggal_kembali,
        ]);

        // Update juga data pengembalian
        if ($peminjaman->pengembalian) {
            $peminjaman->pengembalian->update([
                'tanggal_kembali' => $request->tanggal_kembali,
            ]);
        }

        return redirect()->route('peminjaman.index')->with('success', 'Peminjaman berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $peminjaman = PeminjamanBuku::findOrFail($id);

        // Cek apakah buku sudah dikembalikan
        if ($peminjaman->status === 'dikembalikan') {
            return redirect()->route('peminjaman.index')->with('error', 'Tidak dapat menghapus peminjaman yang sudah dikembalikan.');
        }

        // Kembalikan stok buku
        $buku = Buku::find($peminjaman->buku_id);
        if ($buku) {
            $buku->increment('stok', 1);
        }

        // Hapus relasi pengembalian
        if ($peminjaman->pengembalian) {
            $peminjaman->pengembalian->delete();
        }

        // Hapus relasi denda jika ada
        if ($peminjaman->denda) {
            $peminjaman->denda->delete();
        }

        $peminjaman->delete();

        return redirect()->route('peminjaman.index')->with('success', 'Peminjaman berhasil dihapus dan stok buku dikembalikan.');
    }

    // Method untuk menampilkan detail peminjaman
    public function show($id)
    {
        $peminjaman = PeminjamanBuku::with(['buku', 'user', 'pengembalian', 'denda'])->findOrFail($id);
        return view('admin.peminjaman.show', compact('peminjaman'));
    }
}
