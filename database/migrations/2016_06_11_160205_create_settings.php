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
            $table->string('logo');
            $table->string('button_color');
            $table->string('header_color');
            $table->string('pie_chart_source');
            $table->string('bar_chart_source');
            $table->string('line_chart_source');
            $table->string('geo_chart_source');
            $table->timestamps();
        });

        $settings = new Settings;
        $settings->laralum_version = "2.1.1";
        $settings->website_title = env('APP_NAME', 'My Application');
        $settings->logo = ''; //Default logo will load
        $settings->button_color = "blue";
        $settings->header_color = "#1678c2";
        $settings->pie_chart_source = "highcharts";
        $settings->bar_chart_source = "highcharts";
        $settings->line_chart_source = "highcharts";
        $settings->geo_chart_source = "highcharts";
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
