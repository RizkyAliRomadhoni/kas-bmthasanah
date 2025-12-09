<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFarmAnimalsTable extends Migration
{
    public function up()
    {
        Schema::create('farm_animals', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->nullable()->unique();
            $table->string('nama');
            $table->string('jenis')->nullable();
            $table->enum('gender', ['Jantan','Betina'])->nullable();
            $table->integer('umur')->default(0); // bulan
            $table->double('berat_terakhir', 8, 2)->default(0);
            $table->date('tanggal_masuk')->nullable();
            $table->string('status')->default('Aktif'); // Aktif, Dijual, Mati
            $table->string('kesehatan')->nullable();
            $table->string('foto')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('farm_animals');
    }
}
