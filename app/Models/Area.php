<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Area extends Model
{
    //
    protected $fillable = ['nama_area', 'kapasitas_total'];

    // Relasi Berpikir Komputasional: Satu area memiliki banyak transaksi parkir
    public function parkir()
    {
        return $this->hasMany(Parkir::class, 'area_id', 'id');
    }
}
