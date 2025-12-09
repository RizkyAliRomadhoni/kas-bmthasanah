<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('berat_bulanan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kambing_id')->constrained('kambing')->onDelete('cascade');
            $table->decimal('berat', 8, 2);
            $table->date('tanggal');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('berat_bulanan');
    }
};
