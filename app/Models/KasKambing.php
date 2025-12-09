<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KasKambing extends Model
{
    use HasFactory;

    protected $table = 'kas_kambing';

    protected $fillable = [
        'kambing_id',
        'jenis', // pemasukan atau pengeluaran
        'jumlah',
        'tanggal',
        'keterangan',
    ];

    public function kambing()
    {
        return $this->belongsTo(Kambing::class);
    }
}
