<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KambingMati extends Model
{
    protected $table = 'kambing_matis';
    protected $fillable = ['tanggal', 'jenis', 'harga'];
}