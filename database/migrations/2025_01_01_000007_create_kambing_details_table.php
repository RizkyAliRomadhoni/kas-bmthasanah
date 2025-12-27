<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kambing_details', function (Blueprint $table) {
            $table->id();
            // Hilangkan ->unique() agar satu kas_id bisa punya banyak baris kambing
            $table->unsignedBigInteger('kas_id'); 
            $table->string('jenis')->nullable();
            $table->decimal('harga_beli', 15, 2)->default(0); // Harga per ekor
            $table->decimal('berat_badan', 8, 2)->default(0);
            $table->string('status')->default('Kandang');
            $table->timestamps();

            $table->foreign('kas_id')->references('id')->on('kas')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kambing_details');
    }
};