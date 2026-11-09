<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * Relasi ke seluruh riwayat parkir di area ini.
     */
    public function parkir()
    {
        return $this->hasMany(Parkir::class, 'area_id');
    }

    /**
     * Relasi khusus ke transaksi parkir yang sedang AKTIF (berada di dalam lahan).
     */
    public function parkirAktif()
    {
        return $this->hasMany(Parkir::class, 'area_id')->where('status', 'AKTIF');
    }

    /**
     * Accessor Dinamis: Menghitung sisa kuota slot parkir kosong secara real-time.
     * Penggunaan di Blade/Controller: $area->sisa_kuota
     */
    public function getSisaKuotaAttribute(): int
    {
        $terisi = $this->parkirAktif()->count();
        return max(0, $this->kapasitas_total - $terisi);
    }
}