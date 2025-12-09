<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BeratBulanan extends Model
{
    use HasFactory;

    protected $table = 'berat_bulanan';

    protected $fillable = [
        'kambing_id',
        'berat',
        'tanggal',
    ];

    public function kambing()
    {
        return $this->belongsTo(Kambing::class, 'kambing_id');
    }
}
