<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kas_kambing', function (Blueprint $table) {


            // Tambah kolom tanggal jika belum ada
            if (!Schema::hasColumn('kas_kambing', 'tanggal')) {
                $table->date('tanggal')->nullable()->after('keterangan');
            }

            // Tambah kolom saldo jika diperlukan controller lain
            if (!Schema::hasColumn('kas_kambing', 'saldo')) {
                $table->decimal('saldo', 15, 2)->default(0)->after('jumlah');
            }
        });
    }

    public function down(): void
    {
        Schema::table('kas_kambing', function (Blueprint $table) {


            if (Schema::hasColumn('kas_kambing', 'tanggal')) {
                $table->dropColumn('tanggal');
            }

            if (Schema::hasColumn('kas_kambing', 'saldo')) {
                $table->dropColumn('saldo');
            }
        });
    }
};
