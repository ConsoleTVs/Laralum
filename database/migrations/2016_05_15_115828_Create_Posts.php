<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePosts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
     public function up()
     {
         Schema::create('posts', function (Blueprint $table) {
             $table->increments('id');
             $table->string('image');
             $table->string('title');
             $table->text('description');
             $table->text('body');
             $table->integer('user_id');
             $table->integer('blog_id');
             $table->integer('edited_by')->nullable();
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
         Schema::drop('posts');
     }
}
