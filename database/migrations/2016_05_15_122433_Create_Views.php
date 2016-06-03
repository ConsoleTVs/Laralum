<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateViews extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
     public function up()
     {
         Schema::create('post_views', function (Blueprint $table) {
             $table->increments('id');
             $table->integer('post_id');
             $table->string('ip');
             $table->string('url');
             $table->string('ref');
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
         Schema::drop('post_views');
     }
}
