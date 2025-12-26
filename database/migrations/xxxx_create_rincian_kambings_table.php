<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('rincian_kambings', function (Blueprint $table) {
            $table->id();
            $table->string('jenis');
            $table->integer('qty')->default(0);
            $table->decimal('harga_satuan', 15, 2)->default(0);
            $table->decimal('jumlah', 15, 2)->default(0);
            $table->decimal('ongkir', 15, 2)->default(0);
            $table->decimal('total_hpp', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rincian_kambings');
    }
};
