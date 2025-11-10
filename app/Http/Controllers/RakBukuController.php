<?php

namespace App\Http\Controllers;

use App\Models\RakBuku;
use Illuminate\Http\Request;

class RakBukuController extends Controller
{
    public function index()
    {
        $rak = RakBuku::paginate(10);
        return view('admin.rak.index', compact('rak'));
    }

    public function create()
    {
        return view('admin.rak.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode' => 'required|unique:rak_buku,kode',
            'nama' => 'required|string|max:255',
            'lokasi' => 'nullable|string|max:255',
        ]);

        RakBuku::create($request->all());
        return redirect()->route('rak.index')->with('success', 'Rak Buku berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $rak = RakBuku::findOrFail($id);
        return view('admin.rak.edit', compact('rak'));
    }

    public function update(Request $request, $id)
    {
        $rak = RakBuku::findOrFail($id);
        $request->validate([
            'kode' => 'required|unique:rak_buku,kode,' . $rak->id,
            'nama' => 'required|string|max:255',
            'lokasi' => 'nullable|string|max:255',
        ]);

        $rak->update($request->all());
        return redirect()->route('rak.index')->with('success', 'Rak Buku berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $rak = RakBuku::findOrFail($id);
        $rak->delete();
        return redirect()->route('rak.index')->with('success', 'Rak Buku berhasil dihapus.');
    }
}
