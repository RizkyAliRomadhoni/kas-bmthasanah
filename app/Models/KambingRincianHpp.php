<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KambingRincianHpp extends Model {
    protected $guarded = [];
    public function rincian_bulanan() {
        return $this->hasMany(KambingRincianHppDetail::class, 'kambing_rincian_hpp_id');
    }
}