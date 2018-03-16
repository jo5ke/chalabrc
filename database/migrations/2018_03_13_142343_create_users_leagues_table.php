<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersLeaguesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_league', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->onDelete('cascade');
            $table->integer('league_id')->onDelete('cascade');
            $table->integer('squad_id')->onDelete('cascade');
            $table->integer('money')->unsigned()->nullable();
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
        Schema::dropIfExists('user_league');
    }
}
