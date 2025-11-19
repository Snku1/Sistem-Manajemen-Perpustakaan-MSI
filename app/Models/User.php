<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function peminjaman_buku()
    {
        return $this->hasMany(PeminjamanBuku::class, 'user_id');
    }

    public function denda()
    {
        return $this->hasMany(\App\Models\Denda::class, 'user_id');
    }

}
