<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Permission_Role;

class CreatePermissionRole extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permission_role', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('permission_id');
            $table->integer('role_id');
            $table->timestamps();
        });

        foreach(Laralum::permissions() as $perm) {
            $rel = new Permission_Role;
            $rel->permission_id = \Laralum::permission('id', $perm->id)->id;
            $rel->role_id = \Laralum::role('name', env('ADMINISTRATOR_ROLE_NAME', 'Administrator'))->id;
            $rel->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('permission_role');
    }
}
