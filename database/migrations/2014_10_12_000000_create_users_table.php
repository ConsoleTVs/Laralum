<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\User;

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
            $table->string('password', 60);
            $table->boolean('active');
            $table->boolean('banned');
            $table->string('register_ip');
            $table->string('country_code');
            $table->string('activation_key');
            $table->boolean('su');
            $table->rememberToken();
            $table->timestamps();
        });

        $user = new User;
        $user->name = env('USER_NAME', 'admin');
        $user->email = env('USER_EMAIL', 'admin@admin.com');
        $user->active = true;
        $user->banned = false;
        $user->password = bcrypt(env('USER_PASSWORD', 'admin123'));
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
