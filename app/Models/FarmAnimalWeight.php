<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FarmAnimalWeight extends Model
{
    use HasFactory;

    protected $fillable = ['farm_animal_id','berat','tanggal_update','catatan'];

    public function animal()
    {
        return $this->belongsTo(FarmAnimal::class);
    }
}
