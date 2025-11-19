<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengaturanDenda extends Model
{
    protected $table = 'pengaturan_denda';
    
    protected $fillable = [
        'denda_per_hari',
        'masa_tenggang', 
        'maksimal_hari_peminjaman', // TAMBAH INI
        'aktif'
    ];

    protected $casts = [
        'denda_per_hari' => 'decimal:2',
        'aktif' => 'boolean'
    ];

    // Method untuk mendapatkan pengaturan aktif
    public static function getAktif()
    {
        return static::where('aktif', true)->first();
    }
}