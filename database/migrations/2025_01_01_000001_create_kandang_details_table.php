<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Menggunakan Anonymous Class (return new class) agar tidak bentrok dengan nama file
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('kandangs_details', function (Blueprint $table) {
            $table->id();
            // Menghubungkan ke ID di tabel kas (Pastikan tipe datanya sama: unsignedBigInteger)
            $table->unsignedBigInteger('kas_id')->unique(); 
            
            // Kolom tambahan untuk catatan teknis kandang
            $table->text('catatan_tambahan')->nullable(); 
            $table->timestamps();

            // Definisi Foreign Key
            $table->foreign('kas_id')
                  ->references('id')
                  ->on('kas')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kandangs_details');
    }
};