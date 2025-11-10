<?php

namespace App\Http\Controllers\Pustakawan;

use App\Http\Controllers\Controller;
use App\Models\PeminjamanBuku;

class PeminjamanPustakawanController extends Controller
{
    public function index()
    {
        // Ambil data peminjaman buku
        $peminjaman = PeminjamanBuku::with(['buku', 'user'])
            ->latest()
            ->paginate(10);

        return view('pustakawan.peminjaman.index', compact('peminjaman'));
    }

    public function show($id)
    {
        // Detail peminjaman
        $data = PeminjamanBuku::with(['buku', 'user'])->findOrFail($id);
        return view('pustakawan.peminjaman.show', compact('data'));
    }
}
