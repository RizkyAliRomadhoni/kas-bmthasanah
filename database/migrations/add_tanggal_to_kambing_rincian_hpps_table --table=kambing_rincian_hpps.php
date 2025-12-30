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
        Schema::table('kambing_rincian_hpps', function (Blueprint $table) {
            // Menambahkan kolom tanggal setelah kolom ID
            $table->date('tanggal')->after('id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kambing_rincian_hpps', function (Blueprint $table) {
            $table->dropColumn('tanggal');
        });
    }
};