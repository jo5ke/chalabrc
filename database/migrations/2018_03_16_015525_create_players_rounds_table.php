<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlayersRoundsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('round_player', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('round_id')->onDelete('cascade');
            $table->integer('player_id')->onDelete('cascade');
            $table->integer('start')->unsigned()->nullable();
            $table->integer('sub')->unsigned()->nullable();
            $table->integer('assist')->unsigned()->nullable();
            $table->integer('miss')->unsigned()->nullable();
            $table->integer('k_score')->unsigned()->nullable();
            $table->integer('d_score')->unsigned()->nullable();
            $table->integer('m_score')->unsigned()->nullable();
            $table->integer('a_score')->unsigned()->nullable();
            $table->integer('kd_clean')->unsigned()->nullable();
            $table->integer('m_clean')->unsigned()->nullable();
            $table->integer('k_save')->unsigned()->nullable();
            $table->integer('kd_3strike')->unsigned()->nullable();
            $table->integer('yellow')->unsigned()->nullable();
            $table->integer('red')->unsigned()->nullable();
            $table->integer('own_goal')->unsigned()->nullable();
            $table->integer('captain')->unsigned()->nullable();
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
        Schema::dropIfExists('round_player');
    }
}
