<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('kambing_rincian_hpps', function (Blueprint $table) {
            $table->id(); // Ini otomatis BigInteger Unsigned
            $table->date('tanggal'); 
            $table->string('keterangan'); 
            $table->string('jenis');      
            $table->string('klaster');    
            $table->decimal('harga_awal', 15, 2);
            $table->integer('qty_awal');
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('kambing_rincian_hpps');
    }
};