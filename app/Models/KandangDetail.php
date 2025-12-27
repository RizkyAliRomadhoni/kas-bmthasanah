<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KandangDetail extends Model
{
    use HasFactory;

    protected $table = 'kandangs_details';

    protected $fillable = ['kas_id', 'catatan_tambahan'];

    public function kas()
    {
        return $this->belongsTo(Kas::class, 'kas_id');
    }
}