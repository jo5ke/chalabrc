<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePrivateLeaguesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('private_leagues', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->integer('owner_id')->usnigned()->nullable();
            $table->integer('league_id')->unsigned();
            $table->integer('start_round')->usnigned()->nullable();
            $table->text('emails')->nullable();
            $table->text('invites')->nullable();
            $table->string('code')->nullable()->unique();
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
        Schema::dropIfExists('private_leagues');
    }
}
