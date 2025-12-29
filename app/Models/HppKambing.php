<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HppKambing extends Model
{
    use HasFactory;

    // Nama tabel di database
    protected $table = 'hpp_kambings';

    // Kolom yang boleh diisi manual
    protected $fillable = [
        'jenis', 
        'qty', 
        'harga_satuan', 
        'jumlah', 
        'ongkir', 
        'total_hpp'
    ];
}