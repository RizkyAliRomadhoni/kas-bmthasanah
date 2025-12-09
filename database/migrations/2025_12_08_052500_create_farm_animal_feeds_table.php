<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFarmAnimalFeedsTable extends Migration
{
    public function up()
    {
        Schema::create('farm_animal_feeds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('farm_animal_id')->constrained('farm_animals')->onDelete('cascade');
            $table->string('jenis_pakan');
            $table->integer('jumlah')->comment('gram per hari');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('farm_animal_feeds');
    }
}
