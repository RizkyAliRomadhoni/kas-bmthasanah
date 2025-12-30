<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kambing_rincian_hpp_details', function (Blueprint $table) {
            $table->id();
            // Menghubungkan ke tabel induk (onDelete cascade agar jika induk dihapus, rincian hilang)
            $table->foreignId('kambing_rincian_hpp_id')
                  ->constrained('kambing_rincian_hpps')
                  ->onDelete('cascade');
            
            $table->string('bulan'); // Menandakan rincian ini untuk bulan apa
            $table->decimal('harga_update', 15, 2)->default(0); // Harga per bulan tersebut
            $table->integer('qty_update')->default(0); // Qty per bulan tersebut
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kambing_rincian_hpp_details');
    }
};