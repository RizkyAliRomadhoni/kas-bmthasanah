<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFarmAnimalWeightsTable extends Migration
{
    public function up()
    {
        Schema::create('farm_animal_weights', function (Blueprint $table) {
            $table->id();
            $table->foreignId('farm_animal_id')->constrained('farm_animals')->onDelete('cascade');
            $table->double('berat', 8, 2);
            $table->date('tanggal_update');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('farm_animal_weights');
    }
}
