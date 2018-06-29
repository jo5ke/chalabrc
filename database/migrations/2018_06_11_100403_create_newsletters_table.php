<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewslettersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('newsletters', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('league_id')->unsigned();
            $table->text('title')->nullable();
            $table->text('text')->nullable();
            $table->text('title1')->nullable();
            $table->text('h1')->nullable();
            $table->text('text1')->nullable();
            $table->text('image1')->nullable();
            $table->text('title2')->nullable();
            $table->text('h2')->nullable();
            $table->text('text2')->nullable();
            $table->text('image2')->nullable();
            $table->text('title3')->nullable();
            $table->text('h3')->nullable();
            $table->text('text3')->nullable();
            $table->text('subject')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('newsletters');
    }
}
    // public $title;
    // public $text;

    // public $title1;
    // public $h1;
    // public $text1;
    // public $image1;

    // public $title2;
    // public $h2;    
    // public $text2;
    // public $image2;
    
    // public $title3;
    // public $h3;        
    // public $text3;