<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Role_User;
use App\User;
use App\Role;

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
        $rel->user_id = User::where('email', env('USER_EMAIL', 'admin@admin.com'))->first()->id;
        $rel->role_id = Role::where('name', env('ADMINISTRATOR_ROLE_NAME', 'Administration'))->first()->id;
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
