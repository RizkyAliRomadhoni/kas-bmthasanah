<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FarmAnimal extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode','nama','jenis','gender','umur','berat_terakhir',
        'tanggal_masuk','status','kesehatan','foto'
    ];

    protected $dates = ['tanggal_masuk'];

protected $casts = [
    'tanggal_masuk' => 'date'
];

    public function beratHistory()
    {
        return $this->hasMany(FarmAnimalWeight::class)->orderBy('tanggal_update','desc');
    }

    public function pakan()
    {
        return $this->hasMany(FarmAnimalFeed::class)->orderBy('created_at','desc');
    }
}
