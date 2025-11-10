<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\KategoriBuku;
use App\Models\RakBuku;
use App\Imports\BukuImport;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException;
use Illuminate\Http\Request;

class BukuController extends Controller
{
    /**
     * Menampilkan daftar buku dengan fitur pencarian.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        // Memulai query builder dan memuat relasi
        $query = Buku::with(['kategori', 'rak']);

        // Menerapkan filter pencarian jika ada input
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', '%' . $search . '%')
                  ->orWhere('pengarang', 'like', '%' . $search . '%');
            });
        }

        // Melakukan paginasi dan menambahkan query string ke link paginasi
        $buku = $query->latest()->paginate(10)->withQueryString();

        return view('admin.buku.index', compact('buku'));
    }

    public function create()
    {
        $kategori = KategoriBuku::all();
        $rak = RakBuku::all();
        return view('admin.buku.create', compact('kategori', 'rak'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required',
            'pengarang' => 'required',
            'kategori_id' => 'required|exists:kategori_buku,id',
            'rak_id' => 'required|exists:rak_buku,id',
            'stok' => 'required|integer|min:0',
        ]);

        Buku::create($request->all());
        return redirect()->route('buku.index')->with('success', 'Buku berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $buku = Buku::findOrFail($id);
        $kategori = KategoriBuku::all();
        $rak = RakBuku::all();
        return view('admin.buku.edit', compact('buku', 'kategori', 'rak'));
    }

    public function update(Request $request, $id)
    {
        $buku = Buku::findOrFail($id);
        $request->validate([
            'judul' => 'required',
            'pengarang' => 'required',
            'kategori_id' => 'required|exists:kategori_buku,id',
            'rak_id' => 'required|exists:rak_buku,id',
            'stok' => 'required|integer|min:0',
        ]);

        $buku->update($request->all());
        return redirect()->route('buku.index')->with('success', 'Buku berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $buku = Buku::findOrFail($id);
        $buku->delete();
        return redirect()->route('buku.index')->with('success', 'Buku berhasil dihapus.');
    }
    /**
     * Mengimpor data buku dari file Excel.
     */
    public function importExcel(Request $request)
    {
        // 1. Validasi file yang diunggah harus ada dan berformat .xlsx atau .xls
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        try {
            // 2. Memulai proses impor, memanggil kelas BukuImport
            Excel::import(new BukuImport, $request->file('file'));

        } catch (ValidationException $e) {
            // 3. Menangkap jika ada error validasi di dalam file Excel
            $failures = $e->failures();
            $errorMessages = [];
            foreach ($failures as $failure) {
                // Mengumpulkan semua pesan error per baris
                $errorMessages[] = 'Baris ' . $failure->row() . ': ' . implode(', ', $failure->errors());
            }
            
            // Kembali ke halaman sebelumnya dengan pesan error yang jelas
            return redirect()->route('buku.index')->with('import_error', 'Gagal mengimpor data. Kesalahan:<br>' . implode('<br>', $errorMessages));
        }

        // 4. Jika berhasil, kembali dengan pesan sukses
        return redirect()->route('buku.index')->with('success', 'Data buku berhasil diimpor!');
    }
}
