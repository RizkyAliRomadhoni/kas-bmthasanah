<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    protected $table = 'penjualan';

    protected $fillable = [
        'tanggal',
        'keterangan',
        'tag',
        'harga_jual',
        'hpp',
        'laba',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'harga_jual' => 'integer',
        'hpp' => 'integer',
        'laba' => 'integer',
    ];
}
