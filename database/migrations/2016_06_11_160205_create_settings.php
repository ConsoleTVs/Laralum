<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Settings;

class CreateSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('laralum_version');
            $table->string('website_title');
            $table->string('menu_color');
            $table->string('background_color');
            $table->boolean('light_menu_text');
            $table->boolean('menu_color_to_buttons');
            $table->timestamps();
        });

        $settings = new Settings;
        $settings->laralum_version = "1.4";
        $settings->website_title = "Laralum";
        $settings->menu_color = "#5c6bc0";
        $settings->background_color = "#e8eaf6";
        $settings->light_menu_text = true;
        $settings->menu_color_to_buttons = true;
        $settings->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('settings');
    }
}
