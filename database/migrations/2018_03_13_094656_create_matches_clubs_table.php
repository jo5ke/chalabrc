<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMatchesClubsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('matches_clubs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('match_id')->onDelete('cascade');
            $table->integer('club_id')->onDelete('cascade');
            $table->string('club1_name');         
            $table->string('club2_name'); 
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
        Schema::dropIfExists('matches_clubs');
    }
}
