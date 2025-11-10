<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengembalianBuku extends Model
{
    protected $table = 'pengembalian_buku';
    protected $fillable = ['peminjaman_id', 'tanggal_kembali', 'denda'];

    public function peminjaman()
    {
        return $this->belongsTo(PeminjamanBuku::class, 'peminjaman_id');
    }
    public function buku()
    {
        return $this->belongsTo(Buku::class, 'buku_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function denda()
    {
        return $this->hasOne(Denda::class, 'peminjaman_id');
    }
}
