<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->boolean('active');
            $table->boolean('banned');
            $table->string('register_ip');
            $table->string('country_code');
            $table->string('locale');
            $table->string('activation_key');
            $table->boolean('su');
            $table->rememberToken();
            $table->timestamps();
        });

        $user = \Laralum::newUser();
        $user->name = env('USER_NAME', 'admin');
        $user->email = env('USER_EMAIL', 'admin@admin.com');
        $user->password = bcrypt(env('USER_PASSWORD', 'admin123'));
        $user->active = true;
        $user->banned = false;
        $user->register_ip = "";
        $user->country_code = env('USER_COUNTRY_CODE', 'ES');
        $user->locale = "en";
        $user->activation_key = str_random(25);
        $user->su = true;
        $user->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
    }
}
