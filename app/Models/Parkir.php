<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parkir extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * Casting tipe data tanggal/waktu.
     */
    protected $casts = [
        'waktu_keluar' => 'datetime',
    ];

    /**
     * Relasi ke Model Area.
     */
    public function area()
    {
        return $this->belongsTo(Area::class, 'area_id');
    }

    /**
     * Relasi ke Model User (Pengendara/Petugas).
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Accessor Dinamis: Format Virtual Kode Slot (misal nomor_slot = 4 dan kode_area = "FT" -> "FT004").
     * Penggunaan di Blade/Controller: $parkir->kode_slot_formatted
     */
    public function getKodeSlotFormattedAttribute(): string
    {
        if (!$this->nomor_slot || !$this->area) {
            return '-';
        }
        return $this->area->kode_area . str_pad($this->nomor_slot, 3, '0', STR_PAD_LEFT);
    }
}