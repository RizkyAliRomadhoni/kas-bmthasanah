<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KambingRincianHppDetail extends Model
{
    protected $table = 'kambing_rincian_hpp_details';
    protected $guarded = [];

    public function induk()
    {
        return $this->belongsTo(KambingRincianHpp::class, 'kambing_rincian_hpp_id');
    }
}