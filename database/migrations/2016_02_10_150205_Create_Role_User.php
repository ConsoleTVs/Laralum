<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Role_User;

class CreateRoleUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('role_user', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('role_id');
            $table->integer('user_id');
            $table->timestamps();
        });


        $rel = new Role_User;
        $rel->user_id = \Laralum::user('email', env('USER_EMAIL', 'admin@admin.com'))->id;
        $rel->role_id = \Laralum::role('name', env('ADMINISTRATOR_ROLE_NAME', 'Administrator'))->id;
        $rel->save();

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('role_user');
    }
}
