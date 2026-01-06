<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('neraca_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('nama_akun');
            $table->enum('tipe', ['Aktiva', 'Pasiva']);
            $table->timestamps();
        });
    }
    public function down() {
        Schema::dropIfExists('neraca_accounts');
    }
};