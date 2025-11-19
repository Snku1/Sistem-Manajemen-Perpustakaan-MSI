<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    use HasFactory;

    protected $table = 'buku';

    protected $fillable = [
        'judul',
        'pengarang',
        'penerbit',
        'tahun_terbit',
        'isbn',
        'kategori_id',
        'rak_id',
        'stok',
    ];

    public function kategori()
    {
        return $this->belongsTo(KategoriBuku::class, 'kategori_id');
    }

    public function rak()
    {
        return $this->belongsTo(RakBuku::class, 'rak_id');
    }

    public function peminjaman()
    {
        return $this->hasMany(PeminjamanBuku::class, 'buku_id');
    }
}
