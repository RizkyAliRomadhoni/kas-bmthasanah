<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KambingManualSummary extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang didefinisikan di migration.
     *
     * @var string
     */
    protected $table = 'kambing_manual_summaries';

    /**
     * Field yang boleh diisi (mass assignable).
     * tipe: 'stock' (untuk Stock Kandang) atau 'klaster' (untuk Klaster Bangsalan)
     * label: Nama jenis/klaster (Contoh: MERINO, MARTO, dll)
     * nilai: Angka atau teks manual (Contoh: "15", "0 3 BESAR", dll)
     *
     * @var array
     */
    protected $fillable = [
        'tipe',
        'label',
        'nilai',
    ];

    /**
     * Atau gunakan guarded jika ingin membolehkan semua field.
     * protected $guarded = [];
     */

    /**
     * Scope untuk mempermudah pemisahan data di Controller
     */
    public function scopeStock($query)
    {
        return $query->where('tipe', 'stock');
    }

    public function scopeKlaster($query)
    {
        return $query->where('tipe', 'klaster');
    }
}