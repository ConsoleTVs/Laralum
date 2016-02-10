<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Role;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->boolean('su');
            $table->timestamps();
        });

        $role = new Role;
        $role->name = env('ADMINISTRATOR_ROLE_NAME', 'Administrator');
        $role->su = true;
        $role->save();

        if(env('ADMINISTRATOR_ROLE_NAME') != env('DEFAULT_ROLE_NAME')) {
            $role = new Role;
            $role->name = env('DEFAULT_ROLE_NAME', 'User');
            $role->su = false;
            $role->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('roles');
    }
}
