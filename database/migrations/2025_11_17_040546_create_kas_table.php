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
        Schema::create('kas', function (Blueprint $table) {
            $table->id();

            // tanggal transaksi
            $table->date('tanggal');

            // keterangan transaksi
            $table->string('keterangan');

            // Masuk / Keluar
            $table->enum('jenis_transaksi', ['Masuk', 'Keluar']);

            // jumlah uang
            $table->bigInteger('jumlah')->default(0);

            // akun (Modal, Utang, Piutang, Kambing, Tabungan, dst)
            $table->string('akun')->nullable();

            // saldo berjalan
            $table->bigInteger('saldo')->default(0);

            // user input (diambil dari Auth)
            $table->string('user_input')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kas');
    }
};
