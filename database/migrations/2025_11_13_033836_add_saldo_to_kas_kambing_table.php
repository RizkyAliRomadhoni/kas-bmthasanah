<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::table('kas_kambing', function (Blueprint $table) {
        $table->decimal('saldo', 15, 2)->default(0)->after('jumlah');
    });
}

public function down()
{
    Schema::table('kas_kambing', function (Blueprint $table) {
        $table->dropColumn('saldo');
    });
}
};