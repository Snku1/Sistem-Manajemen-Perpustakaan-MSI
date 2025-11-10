<?php

namespace App\Http\Controllers\Pustakawan;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use Illuminate\Http\Request;

class BukuPustakawanController extends Controller
{
    public function index(Request $request)
    {
        $query = Buku::with('kategori', 'rak');

        // fitur search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%$search%")
                  ->orWhere('pengarang', 'like', "%$search%")
                  ->orWhere('penerbit', 'like', "%$search%")
                  ->orWhereHas('kategori', function ($sub) use ($search) {
                      $sub->where('nama', 'like', "%$search%");
                  })
                  ->orWhereHas('rak', function ($sub) use ($search) {
                      $sub->where('nama', 'like', "%$search%");
                  });
            });
        }

        $bukus = $query->orderBy('judul', 'asc')->paginate(10);

        return view('pustakawan.buku.index', compact('bukus'));
    }
}
