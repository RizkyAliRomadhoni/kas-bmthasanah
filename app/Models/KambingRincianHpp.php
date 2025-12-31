<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KambingRincianHpp extends Model
{
    use HasFactory;

    // Karena Anda menggunakan guarded = [], 
    // kolom 'tag' dan 'tanggal' yang baru otomatis sudah diizinkan (Mass Assignment).
    protected $guarded = [];

    /**
     * Casting Tanggal
     * Ini sangat penting! Supaya kolom 'tanggal' otomatis dianggap sebagai 
     * objek Carbon (Tanggal), jadi di Blade Anda tidak perlu parse manual lagi.
     */
    protected $casts = [
        'tanggal' => 'date',
    ];

    /**
     * Relasi ke Tabel Detail (Sel Laporan Bulanan)
     */
    public function rincian_bulanan()
    {
        // Pastikan nama foreign key sesuai dengan yang ada di migration (kambing_rincian_hpp_id)
        return $this->hasMany(KambingRincianHppDetail::class, 'kambing_rincian_hpp_id');
    }
}