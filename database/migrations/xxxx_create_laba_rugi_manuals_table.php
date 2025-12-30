<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laba_rugi_manuals', function (Blueprint $table) {
            $table->id();
            $table->string('bulan'); // Format: YYYY-MM
            $table->string('kategori'); // 'penyesuaian' atau 'upah'
            $table->bigInteger('nilai')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laba_rugi_manuals');
    }
};