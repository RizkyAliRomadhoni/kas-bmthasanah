<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kambing_manual_summaries', function (Blueprint $table) {
            $table->id();
            $table->string('tipe');  // 'stock' atau 'klaster'
            $table->string('label'); // Contoh: 'MERINO' atau 'MARTO'
            $table->string('nilai')->nullable(); // Nilai yang kamu ketik (Contoh: "15" atau "0 3 BESAR")
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kambing_manual_summaries');
    }
};