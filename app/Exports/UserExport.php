<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class UserExport implements FromCollection, WithHeadings, WithMapping
{
    protected $search;

    public function __construct($search = null)
    {
        $this->search = $search;
    }

    public function collection()
    {
        return User::where('role', 'pustakawan')
            ->when($this->search, function($query) {
                $query->where('name', 'like', '%'.$this->search.'%')
                      ->orWhere('email', 'like', '%'.$this->search.'%');
            })
            ->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Pustakawan',
            'Email',
            'Role'
        ];
    }

    public function map($user): array
    {
        static $i = 1;
        return [
            $i++,
            $user->name,
            $user->email,
            ucfirst($user->role)
        ];
    }
}