<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        // Tabel Utama (Baris data pembelian)
        Schema::create('kambing_rincian_hpps', function (Blueprint $table) {
            $table->id();
            $table->string('keterangan'); // Contoh: HARIYANTO, RATIMIN
            $table->string('jenis');      // Contoh: KAMBING DERE, MERINO
            $table->string('klaster');    // Contoh: MARTO, SUTIK, BOINI
            $table->decimal('harga_awal', 15, 2);
            $table->integer('qty_awal');
            $table->timestamps();
        });

        // Tabel Detail (Kolom histori bulanan: Sep, Oct, Nov, dst)
        Schema::create('kambing_rincian_hpp_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kambing_rincian_hpp_id')->constrained('kambing_rincian_hpps')->onDelete('cascade');
            $table->string('bulan'); // Format: 2025-09
            $table->decimal('harga_update', 15, 2);
            $table->integer('qty_update');
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('kambing_rincian_hpp_details');
        Schema::dropIfExists('kambing_rincian_hpps');
    }
};