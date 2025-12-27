<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('perlengkapan_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('kas_id')->unique(); 
            // Kolom QTY untuk input manual
            $table->integer('qty')->default(0);
            $table->timestamps();

            $table->foreign('kas_id')->references('id')->on('kas')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('perlengkapan_details');
    }
};