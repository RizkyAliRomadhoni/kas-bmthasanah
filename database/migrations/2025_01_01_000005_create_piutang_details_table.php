<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('piutang_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('kas_id')->unique(); 
            $table->string('catatan')->nullable(); 
            $table->timestamps();

            $table->foreign('kas_id')->references('id')->on('kas')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('piutang_details');
    }
};