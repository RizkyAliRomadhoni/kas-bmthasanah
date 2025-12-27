<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pakan_details', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke tabel kas. 
            // Menggunakan unsignedBigInteger karena 'id' di tabel kas adalah bigint(20) UNSIGNED.
            $table->unsignedBigInteger('kas_id')->unique(); 

            // QTY menggunakan decimal agar mendukung angka koma (contoh: 1.507,7 kg)
            $table->decimal('qty_kg', 15, 2)->default(0);

            // Harga per KG menggunakan decimal agar presisi
            $table->decimal('harga_kg', 15, 2)->default(0);

            // Harga Jual per KG (jika pakan dijual kembali atau untuk valuasi stok)
            $table->decimal('harga_jual_kg', 15, 2)->default(0);

            $table->timestamps();

            // Definisi Foreign Key (Integrasi)
            // Jika data transaksi di tabel 'kas' dihapus, maka detail pakan ini otomatis terhapus (cascade)
            $table->foreign('kas_id')
                  ->references('id')
                  ->on('kas')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pakan_details');
    }
};