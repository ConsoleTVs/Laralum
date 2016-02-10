<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Permission;
use App\Permission_Types;

class CreatePermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permissions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('type_id');
            $table->string('slug')->unique();
            $table->string('name');
            $table->text('info');
            $table->boolean('su');
            $table->timestamps();
        });

        $perm = new Permission;
        $perm->slug = "admin.access";
        $perm->name = "Administration Access";
        $perm->info = "Grants access to the administration panel";
        $perm->type_id = Permission_Types::where('type', 'Administration Panel')->first()->id;
        $perm->su = true;
        $perm->save();

        $perm = new Permission;
        $perm->slug = 'admin.users.access';
        $perm->name = 'Users Access';
        $perm->info = 'Grants acces to user page';
        $perm->type_id = Permission_Types::where('type', 'User Administration')->first()->id;
        $perm->su = true;
        $perm->save();

        $perm = new Permission;
        $perm->slug = 'admin.users.create';
        $perm->name = 'Create Users';
        $perm->info = 'Grants acces to user creation';
        $perm->type_id = Permission_Types::where('type', 'User Administration')->first()->id;
        $perm->su = true;
        $perm->save();

        $perm = new Permission;
        $perm->slug = 'admin.users.edit';
        $perm->name = 'Edit Users';
        $perm->info = 'Gives access to user editing';
        $perm->type_id = Permission_Types::where('type', 'User Administration')->first()->id;
        $perm->su = true;
        $perm->save();

        $perm = new Permission;
        $perm->slug = 'admin.users.roles';
        $perm->name = 'Edit User Roles';
        $perm->info = 'Grants access to users role editor';
        $perm->type_id = Permission_Types::where('type', 'User Administration')->first()->id;
        $perm->su = true;
        $perm->save();

        $perm = new Permission;
        $perm->slug = 'admin.users.delete';
        $perm->name = 'Delete Users';
        $perm->info = 'Grants access to user deletion';
        $perm->type_id = Permission_Types::where('type', 'User Administration')->first()->id;
        $perm->su = true;
        $perm->save();

        $perm = new Permission;
        $perm->slug = 'admin.users.settings';
        $perm->name = 'User Settings';
        $perm->info = 'Grants access to user settings';
        $perm->type_id = Permission_Types::where('type', 'User Administration')->first()->id;
        $perm->su = true;
        $perm->save();

        $perm = new Permission;
        $perm->slug = 'admin.roles.access';
        $perm->name = 'Roles Access';
        $perm->info = 'Grants access to roles page';
        $perm->type_id = Permission_Types::where('type', 'Role Administration')->first()->id;
        $perm->su = true;
        $perm->save();

        $perm = new Permission;
        $perm->slug = 'admin.roles.create';
        $perm->name = 'Create Roles';
        $perm->info = 'Grants access to role creation';
        $perm->type_id = Permission_Types::where('type', 'Role Administration')->first()->id;
        $perm->su = true;
        $perm->save();

        $perm = new Permission;
        $perm->slug = 'admin.roles.edit';
        $perm->name = 'Edit Roles';
        $perm->info = 'Grants access to role editing';
        $perm->type_id = Permission_Types::where('type', 'Role Administration')->first()->id;
        $perm->su = true;
        $perm->save();

        $perm = new Permission;
        $perm->slug = 'admin.roles.permissions';
        $perm->name = 'Edit Role Permissions';
        $perm->info = 'Grants access to role permission editing.';
        $perm->type_id = Permission_Types::where('type', 'Role Administration')->first()->id;
        $perm->su = true;
        $perm->save();

        $perm = new Permission;
        $perm->slug = 'admin.roles.delete';
        $perm->name = 'Delete Roles';
        $perm->info = 'Grants access to roles deletion';
        $perm->type_id = Permission_Types::where('type', 'Role Administration')->first()->id;
        $perm->su = true;
        $perm->save();

        $perm = new Permission;
        $perm->slug = 'admin.permissions.access';
        $perm->name = 'Permissions Access';
        $perm->info = 'Grants access to permissions page';
        $perm->type_id = Permission_Types::where('type', 'Permission Administration')->first()->id;
        $perm->su = true;
        $perm->save();

        $perm = new Permission;
        $perm->slug = 'admin.permissions.create';
        $perm->name = 'Create Permissions';
        $perm->info = 'Grants access to permissions creation';
        $perm->type_id = Permission_Types::where('type', 'Permission Administration')->first()->id;
        $perm->su = true;
        $perm->save();

        $perm = new Permission;
        $perm->slug = 'admin.permissions.edit';
        $perm->name = 'Edit Permissions';
        $perm->info = 'Grants access to permissions editing';
        $perm->type_id = Permission_Types::where('type', 'Permission Administration')->first()->id;
        $perm->su = true;
        $perm->save();

        $perm = new Permission;
        $perm->slug = 'admin.permissions.type.create';
        $perm->name = 'Create Permissions Types';
        $perm->info = 'Grants access to permissions type creation';
        $perm->type_id = Permission_Types::where('type', 'Permission Administration')->first()->id;
        $perm->su = true;
        $perm->save();

        $perm = new Permission;
        $perm->slug = 'admin.permissions.type.edit';
        $perm->name = 'Edit Permission Types';
        $perm->info = 'Grants access to permission type editing';
        $perm->type_id = Permission_Types::where('type', 'Permission Administration')->first()->id;
        $perm->su = true;
        $perm->save();

        $perm = new Permission;
        $perm->slug = 'admin.permissions.delete';
        $perm->name = 'Delete Permissions';
        $perm->info = 'Grants access to permission deletion';
        $perm->type_id = Permission_Types::where('type', 'Permission Administration')->first()->id;
        $perm->su = true;
        $perm->save();

        $perm = new Permission;
        $perm->slug = 'admin.permissions.type.delete';
        $perm->name = 'Delete Permission Types';
        $perm->info = 'Grants access to permission types deletion';
        $perm->type_id = Permission_Types::where('type', 'Permission Administration')->first()->id;
        $perm->su = true;
        $perm->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('permissions');
    }
}
