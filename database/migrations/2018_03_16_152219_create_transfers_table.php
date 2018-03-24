<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransfersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transfers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('league_id')->unsigned();
            $table->integer('squad_id')->unsigned();
            $table->integer('season_id')->unsigned();
            $table->integer('round_no')->unsigned();
            $table->text('buy')->nullable();
            $table->text('sell')->nullable();
            $table->integer('ammount_buy')->unsigned();
            $table->integer('ammount_sell')->unsigned();
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
        Schema::dropIfExists('transfers');
    }
}
