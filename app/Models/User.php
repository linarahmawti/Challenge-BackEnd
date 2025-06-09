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
        //'name',
        'email',
        'password'
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Hapus semua method static yang menggunakan DB langsung
    // Biarkan Eloquent menangani operasi database

    /**
     * Mendapatkan nama kolom untuk remember token
     */
    public function getRememberTokenName()
    {
        return 'remember_token';
    }
}
