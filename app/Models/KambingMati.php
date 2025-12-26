<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KambingMati extends Model
{
    protected $table = 'kambing_mati';

    protected $fillable = [
        'tanggal',
        'jenis',
        'harga',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'harga' => 'integer',
    ];
}
