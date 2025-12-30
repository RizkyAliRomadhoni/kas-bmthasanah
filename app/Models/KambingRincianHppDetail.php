<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KambingRincianHppDetail extends Model
{
    use HasFactory;

    protected $table = 'kambing_rincian_hpp_details';

    protected $fillable = [
        'kambing_rincian_hpp_id',
        'bulan',
        'harga_update',
        'qty_update'
    ];

    public function induk()
    {
        return $this->belongsTo(KambingRincianHpp::class, 'kambing_rincian_hpp_id');
    }
}