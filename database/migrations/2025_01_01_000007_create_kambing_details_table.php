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
        // Menghapus tabel lama jika ada untuk menghindari konflik
        Schema::dropIfExists('kambing_details');

        Schema::create('kambing_details', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke tabel Kas (BigInt Unsigned)
            // Tanpa ->unique() karena 1 Kas bisa punya banyak baris Kambing
            $table->unsignedBigInteger('kas_id'); 

            // Jenis Kambing (Contoh: Boer, Merino, Jawa)
            $table->string('jenis')->nullable();

            // Harga Satuan per ekor (Menggunakan bigInteger agar presisi untuk Rupiah)
            $table->bigInteger('harga_satuan')->default(0);

            // Berat Badan dalam KG (Menggunakan decimal agar bisa pakai koma, misal 25.5 kg)
            $table->decimal('berat_badan', 8, 2)->default(0);

            $table->timestamps();

            // Definisi Foreign Key (Integrasi)
            // Jika transaksi di tabel 'kas' dihapus, rincian kambing otomatis terhapus
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
        Schema::dropIfExists('kambing_details');
    }
};