<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HppKambing extends Model
{
    protected $table = 'hpp_kambing';

    protected $fillable = [
        'jenis',
        'qty',
        'harga_satuan',
        'jumlah',
        'ongkir',
        'total_hpp',
    ];

    protected $casts = [
        'qty' => 'integer',
        'harga_satuan' => 'integer',
        'jumlah' => 'integer',
        'ongkir' => 'integer',
        'total_hpp' => 'integer',
    ];
}
