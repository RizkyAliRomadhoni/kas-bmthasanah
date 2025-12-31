<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kambing_rincian_hpps', function (Blueprint $table) {
            // Menambah kolom tag (TA di excel) setelah kolom klaster
            $table->string('tag')->nullable()->after('klaster');
        });
    }

    public function down(): void
    {
        Schema::table('kambing_rincian_hpps', function (Blueprint $table) {
            $table->dropColumn('tag');
        });
    }
};