<?php

namespace App\Imports;

use App\Models\Buku;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class BukuImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Memetakan setiap baris dari Excel ke model Buku
        return new Buku([
            'judul'       => $row['judul'],
            'pengarang'   => $row['pengarang'],
            'kategori_id' => $row['kategori_id'],
            'rak_id'      => $row['rak_id'],
            'stok'        => $row['stok'],
        ]);
    }

    /**
     * Menentukan aturan validasi untuk setiap baris.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            // '*.judul' berarti validasi diterapkan pada kolom 'judul' di setiap baris
            '*.judul' => 'required|string',
            '*.pengarang' => 'required|string',
            '*.kategori_id' => 'required|integer|exists:kategori_buku,id',
            '*.rak_id' => 'required|integer|exists:rak_buku,id',
            '*.stok' => 'required|integer|min:0',
        ];
    }
}
