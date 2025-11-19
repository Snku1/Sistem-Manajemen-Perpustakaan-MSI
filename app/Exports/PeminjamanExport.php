<?php

namespace App\Exports;

use App\Models\PeminjamanBuku;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PeminjamanExport implements FromCollection, WithHeadings, WithMapping
{
    protected $search;

    public function __construct($search = null)
    {
        $this->search = $search;
    }

    public function collection()
    {
        return PeminjamanBuku::with(['buku', 'user'])
            ->when($this->search, function($query) {
                $query->whereHas('user', function($q) {
                    $q->where('name', 'like', '%'.$this->search.'%');
                })
                ->orWhereHas('buku', function($q) {
                    $q->where('judul', 'like', '%'.$this->search.'%');
                });
            })
            ->latest()
            ->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Peminjam',
            'Judul Buku',
            'Tanggal Pinjam',
            'Tanggal Kembali',
            'Status'
        ];
    }

    public function map($peminjaman): array
    {
        static $i = 1;
        return [
            $i++,
            $peminjaman->user->name ?? '-',
            $peminjaman->buku->judul ?? '-',
            \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->format('d M Y'),
            $peminjaman->tanggal_kembali ? \Carbon\Carbon::parse($peminjaman->tanggal_kembali)->format('d M Y') : '-',
            $peminjaman->status === 'dipinjam' ? 'Dipinjam' : 'Dikembalikan'
        ];
    }
}