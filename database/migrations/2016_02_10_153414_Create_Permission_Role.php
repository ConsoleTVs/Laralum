<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Permission;
use App\Permission_Role;
use App\Role;

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

        foreach(Permission::all() as $perm) {
            $rel = new Permission_Role;
            $rel->permission_id = Permission::where('id', $perm->id)->first()->id;
            $rel->role_id = Role::where('name', env('ADMINISTRATOR_ROLE_NAME', 'Administrator'))->first()->id;
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
