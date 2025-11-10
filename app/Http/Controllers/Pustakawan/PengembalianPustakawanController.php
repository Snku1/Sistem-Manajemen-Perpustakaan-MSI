<?php

namespace App\Http\Controllers\Pustakawan;

use App\Http\Controllers\Controller;
use App\Models\PengembalianBuku;

class PengembalianPustakawanController extends Controller
{
    public function index()
    {
        // Ambil data pengembalian buku
        $pengembalian = PengembalianBuku::with(['buku', 'user'])
            ->latest()
            ->paginate(10);

        return view('pustakawan.pengembalian.index', compact('pengembalian'));
    }

    public function show($id)
    {
        // Detail pengembalian
        $data = PengembalianBuku::with(['buku', 'user'])->findOrFail($id);
        return view('pustakawan.pengembalian.show', compact('data'));
    }
}
