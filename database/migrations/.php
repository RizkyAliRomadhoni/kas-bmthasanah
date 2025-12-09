<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// ✅ Nama kelas harus sesuai dengan nama file
class AddAkunToKasTable extends Migration
{
    /**
     * Tambahkan kolom 'akun' ke tabel 'kas'
     */
    public function up()
    {
        Schema::table('kas', function (Blueprint $table) {
            $table->string('akun')->nullable(); // Kolom baru
        });
    }

    /**
     * Hapus kolom 'akun' jika migrasi di-rollback
     */
    public function down()
    {
        Schema::table('kas', function (Blueprint $table) {
            $table->dropColumn('akun');
        });
    }
}