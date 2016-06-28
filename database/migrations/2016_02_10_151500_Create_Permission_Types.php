<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Permission_Types;

class CreatePermissionTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permission_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type');
            $table->boolean('su');
            $table->timestamps();
        });

        $types = [
            'Administration Panel',
            'User Administration',
            'Role Administration',
            'Permission Administration',
            'Blog Administration',
            'Post Administration',
            'Developer Mode',
            'File Manager',
            'Settings',
        ];

        foreach($types as $t) {
            $type = new Permission_types;
            $type->type = $t;
            $type->su = true;
            $type->save();
        }
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('permission_types');
    }
}
