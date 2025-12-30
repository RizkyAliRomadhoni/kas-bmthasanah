<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KambingRincianHpp extends Model
{
    use HasFactory;

    protected $table = 'kambing_rincian_hpps';

    protected $fillable = [
        'tanggal',
        'keterangan',
        'jenis',
        'klaster',
        'harga_awal',
        'qty_awal'
    ];

    // Relasi ke tabel detail histori bulanan
    public function rincian_bulanan()
    {
        return $this->hasMany(KambingRincianHppDetail::class, 'kambing_rincian_hpp_id');
    }
}