<?php

namespace App\Http\Controllers\Pustakawan;

use App\Http\Controllers\Controller;
use App\Models\PengembalianBuku;
use Illuminate\Http\Request;

class PengembalianPustakawanController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        // Query dasar + relasi
        $query = PengembalianBuku::with(['buku', 'user']);

        // Jika melakukan pencarian
        if ($search) {
            $query->whereHas('buku', function ($q) use ($search) {
                $q->where('judul', 'like', '%' . $search . '%');
            })->orWhereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            });
        }

        // Pagination
        $pengembalian = $query->latest()->paginate(10)->withQueryString();

        return view('pustakawan.pengembalian.index', compact('pengembalian', 'search'));
    }

    public function show($id)
    {
        $data = PengembalianBuku::with(['buku', 'user'])->findOrFail($id);
        return view('pustakawan.pengembalian.show', compact('data'));
    }
}
