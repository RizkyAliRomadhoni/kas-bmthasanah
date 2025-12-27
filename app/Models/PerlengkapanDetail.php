<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerlengkapanDetail extends Model
{
    use HasFactory;

    protected $table = 'perlengkapan_details';

    protected $fillable = ['kas_id', 'qty'];

    public function kas()
    {
        return $this->belongsTo(Kas::class, 'kas_id');
    }
}