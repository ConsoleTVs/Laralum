<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->string('slug')->unique();
            $table->boolean('assignable');
            $table->boolean('su');
            $table->timestamps();
        });

        $permissions = [
            'laralum.access',
            'laralum.users.access',
            'laralum.users.create',
            'laralum.users.edit',
            'laralum.users.roles',
            'laralum.users.delete',
            'laralum.users.settings',
            'laralum.roles.access',
            'laralum.roles.create',
            'laralum.roles.edit',
            'laralum.roles.permissions',
            'laralum.roles.delete',
            'laralum.permissions.access',
            'laralum.permissions.create',
            'laralum.permissions.edit',
            'laralum.permissions.delete',
            'laralum.blogs.access',
            'laralum.blogs.create',
            'laralum.blogs.edit',
            'laralum.blogs.posts',
            'laralum.blogs.delete',
            'laralum.posts.access',
            'laralum.posts.create',
            'laralum.posts.edit',
            'laralum.posts.view',
            'laralum.posts.comments',
            'laralum.posts.graphics',
            'laralum.posts.delete',
            'laralum.files.access',
            'laralum.files.upload',
            'laralum.files.download',
            'laralum.files.delete',
            'laralum.documents.create',
            'laralum.documents.edit',
            'laralum.documents.delete',
            'laralum.settings.access',
            'laralum.settings.edit',
            'laralum.CRUD.access',
        ];

        foreach($permissions as $permission) {
            $perm = \Laralum::newPermission();
            $perm->slug = $permission;
            $perm->assignable = true;
            $perm->su = true;
            $perm->save();
        }


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
