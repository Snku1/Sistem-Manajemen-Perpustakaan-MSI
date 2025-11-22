<?php

namespace App\Http\Controllers\Pustakawan;

use App\Http\Controllers\Controller;
use App\Models\PeminjamanBuku;
use Illuminate\Http\Request;

class PeminjamanPustakawanController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        // Query dasar + relasi
        $query = PeminjamanBuku::with(['buku', 'user']);

        // Filter pencarian
        if ($search) {
            $query->whereHas('buku', function ($q) use ($search) {
                $q->where('judul', 'like', '%' . $search . '%');
            })->orWhereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            });
        }

        // Pagination
        $peminjaman = $query->latest()->paginate(10)->withQueryString();

        return view('pustakawan.peminjaman.index', compact('peminjaman', 'search'));
    }

    public function show($id)
    {
        $data = PeminjamanBuku::with(['buku', 'user'])->findOrFail($id);
        return view('pustakawan.peminjaman.show', compact('data'));
    }
}
