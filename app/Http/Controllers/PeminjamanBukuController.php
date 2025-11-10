<?php
namespace App\Http\Controllers;

use App\Models\PeminjamanBuku;
use App\Models\PengembalianBuku;
use App\Models\Buku;
use App\Models\User;
use App\Models\Denda;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PeminjamanBukuController extends Controller
{
  public function index()
    {
        // Ambil semua data peminjaman, bisa difilter kalau mau
        $peminjaman = PeminjamanBuku::with(['buku', 'user'])->latest()->get();

        return view('admin.peminjaman.index', compact('peminjaman'));
    }

    public function create()
    {
        $buku = Buku::all();
        $users = User::all();

        return view('admin.peminjaman.create', compact('buku', 'users'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'buku_id' => 'required',
            'user_id' => 'required',
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali' => 'required|date|after_or_equal:tanggal_pinjam',
        ]);

        // Ambil buku yang dipinjam
        $buku = Buku::findOrFail($request->buku_id);

        // Cek apakah stok cukup
        if ($buku->stok <= 0) {
            return redirect()->back()->with('error', 'Stok buku sudah habis!');
        }

        // Simpan peminjaman
        $peminjaman = PeminjamanBuku::create([
            'buku_id' => $request->buku_id,
            'user_id' => $request->user_id,
            'tanggal_pinjam' => $request->tanggal_pinjam,
            'tanggal_kembali' => $request->tanggal_kembali,
        ]);

        // Simpan otomatis ke pengembalian
        PengembalianBuku::create([
            'peminjaman_id' => $peminjaman->id,
            'tanggal_kembali' => $request->tanggal_kembali,
        ]);

        // Kurangi stok buku
        $buku->stok -= 1;
        $buku->save();

        return redirect()->route('peminjaman.index')->with('success', 'Peminjaman berhasil ditambahkan dan data pengembalian dibuat.');
    }
    public function edit($id)
    {
        $peminjaman = PeminjamanBuku::findOrFail($id);
        $buku = Buku::all(); // ambil semua buku untuk dropdown
        $users = User::all(); 
        return view('admin.peminjaman.edit', compact('peminjaman', 'buku', 'users'));
    }

    public function update(Request $request, $id)
    {
        $peminjaman = PeminjamanBuku::findOrFail($id);

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'buku_id' => 'required|exists:buku,id',
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali' => 'nullable|date',
        ]);

        $peminjaman->update($request->all());

        return redirect()->route('peminjaman.index')->with('success', 'Peminjaman berhasil diperbarui.');
    }
    public function destroy(string $id)
    {
        $peminjaman = PeminjamanBuku::findOrFail($id);

        // Hapus relasi jika ada
        if ($peminjaman->pengembalian) {
            $peminjaman->pengembalian->delete();
        }

        if ($peminjaman->detailPeminjaman) {
            $peminjaman->detailPeminjaman()->delete();
        }

        $peminjaman->delete();

        return redirect()->route('peminjaman.index')->with('success', 'Peminjaman dan data terkait berhasil dihapus.');
    }

}
