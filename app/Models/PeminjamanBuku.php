<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeminjamanBuku extends Model
{
    use HasFactory;

    protected $table = 'peminjaman_buku';

    protected $fillable = [
        'buku_id',
        'user_id',
        'tanggal_pinjam',
        'tanggal_kembali',
        'status',
    ];

    public function buku()
    {
        return $this->belongsTo(Buku::class, 'buku_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

        // Relasi ke Pengembalian
    public function pengembalian()
    {
        return $this->hasOne(PengembalianBuku::class, 'peminjaman_id');
    }

    public function denda()
    {
        return $this->hasOne(Denda::class, 'peminjaman_id');
    }
}
