<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Denda;
use App\Models\PengaturanDenda;
use Illuminate\Http\Request;

class DendaController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');

        $denda = Denda::with([
            'peminjaman.buku',
            'peminjaman.user',
            'peminjaman.pengembalian', // TAMBAHKAN INI
            'user'
        ])
            ->when($search, function ($query) use ($search) {
                $query->whereHas('peminjaman.user', function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%');
                })
                    ->orWhereHas('peminjaman.buku', function ($q) use ($search) {
                        $q->where('judul', 'like', '%' . $search . '%');
                    });
            })
            ->latest()
            ->paginate(10);

        $pengaturan = PengaturanDenda::getAktif();

        return view('admin.denda.index', compact('denda', 'pengaturan'));
    }

    public function pengaturan()
    {
        $pengaturan = PengaturanDenda::getAktif();
        return view('admin.denda.pengaturan', compact('pengaturan'));
    }

    public function updatePengaturan(Request $request)
    {
        $request->validate([
            'denda_per_hari' => 'required|numeric|min:0',
            'masa_tenggang' => 'required|integer|min:0',
            'maksimal_hari_peminjaman' => 'required|integer|min:1|max:30', // VALIDASI BARU
        ]);

        // Nonaktifkan semua pengaturan sebelumnya
        PengaturanDenda::where('aktif', true)->update(['aktif' => false]);

        // Buat pengaturan baru
        PengaturanDenda::create([
            'denda_per_hari' => $request->denda_per_hari,
            'masa_tenggang' => $request->masa_tenggang,
            'maksimal_hari_peminjaman' => $request->maksimal_hari_peminjaman, // TAMBAH INI
            'aktif' => true
        ]);

        return redirect()->route('denda.index')
            ->with('success', 'Pengaturan denda dan batas waktu berhasil diperbarui.');
    }
}
