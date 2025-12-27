<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('operasional_details', function (Blueprint $table) {
            $table->id();
            // Menghubungkan ke ID di tabel kas
            $table->unsignedBigInteger('kas_id')->unique(); 
            // Kolom untuk catatan tambahan teknis jika diperlukan
            $table->string('catatan')->nullable(); 
            $table->timestamps();

            // Relasi Foreign Key
            $table->foreign('kas_id')->references('id')->on('kas')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('operasional_details');
    }
};