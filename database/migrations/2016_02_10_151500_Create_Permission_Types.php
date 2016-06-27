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

        $type = new Permission_types;
        $type->type = "Administration Panel";
        $type->su = true;
        $type->save();

        $type = new Permission_types;
        $type->type = "User Administration";
        $type->su = true;
        $type->save();

        $type = new Permission_types;
        $type->type = "Role Administration";
        $type->su = true;
        $type->save();

        $type = new Permission_types;
        $type->type = "Permission Administration";
        $type->su = true;
        $type->save();

        $type = new Permission_types;
        $type->type = "Blog Administration";
        $type->su = true;
        $type->save();

        $type = new Permission_types;
        $type->type = "Post Administration";
        $type->su = true;
        $type->save();

        $type = new Permission_types;
        $type->type = "Developer Mode";
        $type->su = true;
        $type->save();

        $type = new Permission_types;
        $type->type = "Settings";
        $type->su = true;
        $type->save();
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
