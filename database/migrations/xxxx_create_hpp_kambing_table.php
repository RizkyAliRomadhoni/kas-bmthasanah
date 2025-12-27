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
        Schema::create('hpp_kambing', function (Blueprint $table) {
            $table->id();
            $table->string('jenis')->nullable();
            $table->integer('qty')->default(0);
            $table->bigInteger('harga_satuan')->default(0);
            $table->bigInteger('jumlah')->default(0); // qty * harga_satuan
            $table->bigInteger('ongkir')->default(0);
            $table->bigInteger('total_hpp')->default(0); // jumlah + ongkir
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hpp_kambing');
    }
};