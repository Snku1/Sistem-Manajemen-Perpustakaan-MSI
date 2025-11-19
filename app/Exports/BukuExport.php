<?php

namespace App\Exports;

use App\Models\Buku;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class BukuExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Buku::withCount([
            'peminjaman as dipinjam' => function($q){
                $q->where('status', 'dipinjam');
            }
        ])
        ->withCount([
            'peminjaman as dikembalikan' => function($q){
                $q->where('status', 'dikembalikan');
            }
        ])->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Judul Buku',
            'Stok Total',
            'Dipinjam',
            'Dikembalikan',
            'Tanggal Input'
        ];
    }

    public function map($buku): array
    {
        static $i = 1;
        return [
            $i++,
            $buku->judul,
            $buku->stok,
            $buku->dipinjam ?? 0,
            $buku->dikembalikan ?? 0,
            $buku->created_at->format('d M Y')
        ];
    }
}