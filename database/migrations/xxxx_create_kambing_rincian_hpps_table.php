<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kambing_rincian_hpps', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal'); // Tanggal masuk kambing
            $table->string('keterangan'); // Contoh: Hariyanto, Ratimin
            $table->string('jenis'); // Contoh: Kambing Dere, Merino
            $table->string('klaster'); // Contoh: Marto, Sutik
            $table->decimal('harga_awal', 15, 2); // Harga modal beli
            $table->integer('qty_awal'); // Jumlah ekor awal
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kambing_rincian_hpps');
    }
};