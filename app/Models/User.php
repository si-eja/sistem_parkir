<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Kolom yang dapat diisi secara massal (mass assignable).
     */
    protected $guarded = [];

    /**
     * Kolom yang disembunyikan saat serialisasi JSON/Array.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casting tipe data kolom.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Boot function model: Auto-generate QR Code Token permanen saat akun baru dibuat.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            if (empty($user->qr_code_token)) {
                $user->qr_code_token = 'PARKIR-' . Str::upper(Str::random(10));
            }
        });
    }

    /**
     * Relasi ke seluruh riwayat transaksi parkir user ini.
     */
    public function parkir()
    {
        return $this->hasMany(Parkir::class, 'user_id');
    }

    /**
     * Helper check role user.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'ADMIN';
    }

    public function isJukir(): bool
    {
        return $this->role === 'JUKIR';
    }

    public function isPengendara(): bool
    {
        return $this->role === 'PENGENDARA';
    }
}