<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('upah_details', function (Blueprint $table) {
            $table->id();
            // Menghubungkan ke ID di tabel kas
            $table->unsignedBigInteger('kas_id')->unique(); 
            // Kolom untuk rincian (misal: "12 Hari kerja", "Lembur", dll)
            $table->string('catatan')->nullable(); 
            $table->timestamps();

            // Relasi Foreign Key
            $table->foreign('kas_id')->references('id')->on('kas')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('upah_details');
    }
};