<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Parkir extends Model
{
    //
    protected $fillable = ['plat_nomor', 'area_id', 'waktu_keluar', 'status', 'input_oleh'];

    protected $casts = [
        'waktu_keluar' => 'datetime',
    ];

    // Relasi: Transaksi ini dimiliki oleh/berada di area tertentu
    public function area(): BelongsTo {
        return $this->belongsTo(Area::class);
    }
}
