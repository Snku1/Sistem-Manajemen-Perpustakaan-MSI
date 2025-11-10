<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RakBuku extends Model
{
    use HasFactory;

    protected $table = 'rak_buku';

    protected $fillable = [
        'kode',
        'nama',
        'lokasi',
    ];

    public function buku()
    {
        return $this->hasMany(Buku::class, 'rak_id');
    }
}
