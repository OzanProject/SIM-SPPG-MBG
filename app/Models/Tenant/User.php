<?php

namespace App\Models\Tenant;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    // Model ini TIDAK mengunci koneksi ke 'central'
    // Ia akan otomatis mengikuti Database Tenant (SQLite) yang sedang aktif
    protected $connection = null;
    
    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'password',
        'profile_photo',
        'whatsapp',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
