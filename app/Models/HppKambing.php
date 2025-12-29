<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HppKambing extends Model
{
    protected $table = 'hpp_kambings';
    protected $fillable = ['jenis', 'qty', 'harga_satuan', 'jumlah', 'ongkir', 'total_hpp'];
}