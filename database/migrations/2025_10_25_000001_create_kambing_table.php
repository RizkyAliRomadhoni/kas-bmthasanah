<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKambingTable extends Migration
{
    public function up(): void
    {
        Schema::create('kambing', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique();
            $table->string('nama');
            $table->integer('jumlah')->default(1);
            $table->decimal('berat_awal', 8, 2)->nullable();
            $table->string('konsumsi_pakan')->nullable();
            $table->decimal('harga_beli', 15, 2)->nullable();
            $table->decimal('harga_jual', 15, 2)->nullable();
            $table->string('deskripsi');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kambing');
    }
}
