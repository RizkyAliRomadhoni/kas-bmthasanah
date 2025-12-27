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
        'akun',
        'saldo',
        'user_input'
    ];

    // Relasi ke detail pakan
    public function pakanDetail()
    {
        return $this->hasOne(PakanDetail::class, 'kas_id');
    }

    // Tambahkan fungsi ini di dalam class Kas yang sudah ada
public function kandangDetail()
{
    return $this->hasOne(KandangDetail::class, 'kas_id');
}

// Tambahkan fungsi ini di dalam class Kas Anda
public function perlengkapanDetail()
{
    return $this->hasOne(PerlengkapanDetail::class, 'kas_id');
}

// Tambahkan fungsi ini di dalam class Kas Anda (copy-paste di bawah relasi lainnya)
public function operasionalDetail()
{
    return $this->hasOne(OperasionalDetail::class, 'kas_id');
}

// Tambahkan fungsi ini di dalam class Kas Anda
public function hutangDetail()
{
    return $this->hasOne(HutangDetail::class, 'kas_id');
}

// Tambahkan fungsi ini di dalam class Kas Anda
public function piutangDetail()
{
    return $this->hasOne(PiutangDetail::class, 'kas_id');
}

}