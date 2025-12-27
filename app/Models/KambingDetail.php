<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KambingDetail extends Model
{
    use HasFactory;

    protected $table = 'kambing_details';
    protected $fillable = ['kas_id', 'jenis', 'qty', 'berat_badan', 'status'];

    public function kas()
    {
        return $this->belongsTo(Kas::class, 'kas_id');
    }
}