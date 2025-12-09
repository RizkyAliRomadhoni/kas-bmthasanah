<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FarmAnimalFeed extends Model
{
    use HasFactory;

    protected $fillable = ['farm_animal_id','jenis_pakan','jumlah','catatan'];

    public function animal()
    {
        return $this->belongsTo(FarmAnimal::class);
    }
}
