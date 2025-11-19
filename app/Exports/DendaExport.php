<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class DendaExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return User::where('role', 'pustakawan')
            ->withCount('denda')
            ->withSum('denda', 'total_denda')
            ->withAvg('denda', 'total_denda')
            ->withMax('denda', 'total_denda')
            ->withMin('denda', 'total_denda')
            ->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Pustakawan',
            'Jumlah Denda',
            'Total Denda',
            'Rata-rata Denda',
            'Denda Tertinggi',
            'Denda Terendah'
        ];
    }

    public function map($user): array
    {
        static $i = 1;
        return [
            $i++,
            $user->name,
            $user->denda_count,
            $user->denda_sum_total_denda ?? 0,
            $user->denda_avg_total_denda ?? 0,
            $user->denda_max_total_denda ?? 0,
            $user->denda_min_total_denda ?? 0
        ];
    }
}