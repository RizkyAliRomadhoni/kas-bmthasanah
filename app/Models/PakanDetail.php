<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PakanDetail extends Model
{
    use HasFactory;

    protected $table = 'pakan_details';

    protected $fillable = [
        'kas_id',
        'qty_kg',
        'harga_kg',
        'harga_jual_kg',
    ];

    // Relasi balik ke Kas
    public function kas()
    {
        return $this->belongsTo(Kas::class, 'kas_id');
    }
}