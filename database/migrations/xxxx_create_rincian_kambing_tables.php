<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel 1: Harga Kambing (HPP)
        Schema::create('hpp_kambings', function (Blueprint $table) {
            $table->id();
            $table->string('jenis');
            $table->integer('qty');
            $table->bigInteger('harga_satuan');
            $table->bigInteger('jumlah'); 
            $table->bigInteger('ongkir')->default(0);
            $table->bigInteger('total_hpp'); 
            $table->timestamps();
        });

        // Tabel 2: Kambing Mati
        Schema::create('kambing_matis', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->string('jenis');
            $table->bigInteger('harga'); 
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hpp_kambings');
        Schema::dropIfExists('kambing_matis');
    }
};