<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kambing extends Model
{
    use HasFactory;

    protected $table = 'kambing';

    protected $fillable = [
        'kode',
        'nama',
        'jumlah',
        'berat_awal',
        'konsumsi_pakan',
        'harga_beli',
        'harga_jual',
        'deskripsi',
    ];

    public function beratBulanan()
    {
        return $this->hasMany(BeratBulanan::class);
    }

    public function kas()
    {
        return $this->hasMany(KasKambing::class);
    }
}
