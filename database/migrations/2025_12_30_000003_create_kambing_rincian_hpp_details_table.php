<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('kambing_rincian_hpp_details', function (Blueprint $table) {
            $table->id();
            
            // PAKAI CARA MANUAL AGAR PASTI MATCH DENGAN TABEL INDUK
            $table->unsignedBigInteger('kambing_rincian_hpp_id');
            
            $table->string('bulan'); 
            $table->decimal('harga_update', 15, 2)->default(0);
            $table->integer('qty_update')->default(0);
            $table->timestamps();

            // DEFINISIKAN FOREIGN KEY SECARA TEGAS
            $table->foreign('kambing_rincian_hpp_id', 'fk_kambing_hpp_id')
                  ->references('id')
                  ->on('kambing_rincian_hpps')
                  ->onDelete('cascade');
        });
    }
    public function down(): void {
        Schema::dropIfExists('kambing_rincian_hpp_details');
    }
};