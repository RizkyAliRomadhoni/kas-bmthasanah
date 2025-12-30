<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('kambing_rincian_periodes', function (Blueprint $table) {
            $table->id();
            $table->string('bulan')->unique(); 
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('kambing_rincian_periodes');
    }
};