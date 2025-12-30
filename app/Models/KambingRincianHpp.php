<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KambingRincianHpp extends Model
{
    use HasFactory;
    protected $guarded = [];

    // Menghubungkan baris ke detail bulanan (sel tabel)
    public function rincian_bulanan()
    {
        return $this->hasMany(KambingRincianHppDetail::class, 'kambing_rincian_hpp_id');
    }
}