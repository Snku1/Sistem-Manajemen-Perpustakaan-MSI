<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengembalianBuku extends Model
{
    use HasFactory;

    protected $table = 'pengembalian_buku'; // pastikan nama tabel benar

    protected $fillable = [
        'peminjaman_id',
        'tanggal_kembali', 
        'denda'
    ];

    // Relationship ke peminjaman
    public function peminjaman()
    {
        return $this->belongsTo(PeminjamanBuku::class, 'peminjaman_id');
    }

    // Relationship ke user MELALUI peminjaman
    public function user()
    {
        return $this->hasOneThrough(
            User::class,
            PeminjamanBuku::class,
            'id', // Foreign key pada peminjaman_buku
            'id', // Foreign key pada users
            'peminjaman_id', // Local key pada pengembalian_buku
            'user_id' // Local key pada peminjaman_buku
        );
    }

    // Relationship ke buku MELALUI peminjaman
    public function buku()
    {
        return $this->hasOneThrough(
            Buku::class,
            PeminjamanBuku::class,
            'id', // Foreign key pada peminjaman_buku
            'id', // Foreign key pada buku
            'peminjaman_id', // Local key pada pengembalian_buku
            'buku_id' // Local key pada peminjaman_buku
        );
    }
}