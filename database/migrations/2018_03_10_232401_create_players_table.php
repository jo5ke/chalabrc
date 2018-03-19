<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlayersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('players', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('club_id')->unsigned();  
            $table->integer('league_id')->unsigned();  
            $table->string('first_name',25);          
            $table->string('last_name',25);          
            $table->string('position',5);          
            $table->integer('price')->nullable()->unsigned();          
            $table->integer('number')->nullable()->unsigned();       
            $table->integer('wont_play')->unsigned()->default(0);          
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
        Schema::dropIfExists('players');
    }
}
