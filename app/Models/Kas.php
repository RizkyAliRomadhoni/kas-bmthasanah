<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kas extends Model
{
    use HasFactory;

    protected $table = 'kas';

    protected $fillable = [
        'tanggal',
        'keterangan',
        'jenis_transaksi',
        'jumlah',
        'saldo',
        'akun', // Tambahkan kolom ini
        'user_input',
    ];
}
