<?php

namespace App\Exports;

use App\Models\PengembalianBuku;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PengembalianExport implements FromCollection, WithHeadings, WithMapping
{
    protected $search;

    public function __construct($search = null)
    {
        $this->search = $search;
    }

    public function collection()
    {
        return PengembalianBuku::with([
            'peminjaman.user',
            'peminjaman.buku',
            'peminjaman.denda'
        ])
        ->when($this->search, function ($query) {
            $query->whereHas('peminjaman.user', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            })
            ->orWhereHas('peminjaman.buku', function ($q) {
                $q->where('judul', 'like', '%' . $this->search . '%');
            });
        })
        ->latest()
        ->get();
    }

    public function headings(): array
    {
        return [
            'Nama Peminjam',
            'Judul Buku',
            'Tanggal Pinjam',
            'Tanggal Kembali Seharusnya',
            'Tanggal Dikembalikan',
            'Hari Terlambat',
            'Total Denda',
            'Status'
        ];
    }

    public function map($pengembalian): array
    {
        $hariTerlambat = $pengembalian->peminjaman->denda ? $pengembalian->peminjaman->denda->jumlah_hari : 0;
        $totalDenda = $pengembalian->peminjaman->denda ? $pengembalian->peminjaman->denda->total_denda : 0;

        return [
            $pengembalian->peminjaman->user->name,
            $pengembalian->peminjaman->buku->judul,
            $pengembalian->peminjaman->tanggal_pinjam,
            $pengembalian->peminjaman->tanggal_kembali,
            $pengembalian->tanggal_kembali,
            $hariTerlambat,
            'Rp ' . number_format($totalDenda, 0, ',', '.'),
            $pengembalian->peminjaman->status
        ];
    }
}