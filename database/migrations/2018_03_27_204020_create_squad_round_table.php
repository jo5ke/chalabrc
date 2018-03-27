<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSquadRoundTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('squad_round', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('round_id')->onDelete('cascade');
            $table->integer('squad_id')->onDelete('cascade');
            $table->integer('league_id')->onDelete('cascade');
            $table->integer('points')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('squad_round');
    }
}
