<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->string('color');
            $table->boolean('assignable');
            $table->boolean('allow_editing');
            $table->boolean('su');
            $table->timestamps();
        });

        $role = \Laralum::newRole();
        $role->name = env('ADMINISTRATOR_ROLE_NAME', 'Administrator');
        $role->color = "#DB2828";
        $role->assignable = false;
        $role->allow_editing = false;
        $role->su = true;
        $role->save();

        if(env('ADMINISTRATOR_ROLE_NAME', 'Administrator') != env('DEFAULT_ROLE_NAME', 'User')) {
            $role = \Laralum::newRole();
            $role->name = env('DEFAULT_ROLE_NAME', 'User');
            $role->color = "#000000";
            $role->assignable = true;
            $role->allow_editing = true;
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
