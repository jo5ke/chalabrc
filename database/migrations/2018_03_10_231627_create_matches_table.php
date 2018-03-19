<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('matches', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('round_id')->unsigned();   
            $table->foreign('round_id')->references('id')->on('rounds')->onUpdate('cascade')->onDelete('cascade');
            $table->integer('league_id')->unsigned();   
            $table->string('club1_name')->nullable();         
            $table->string('club2_name')->nullable();         
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
        Schema::dropIfExists('matches');
    }
}
