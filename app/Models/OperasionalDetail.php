<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OperasionalDetail extends Model
{
    use HasFactory;

    protected $table = 'operasional_details';

    protected $fillable = ['kas_id', 'catatan'];

    public function kas()
    {
        return $this->belongsTo(Kas::class, 'kas_id');
    }
}