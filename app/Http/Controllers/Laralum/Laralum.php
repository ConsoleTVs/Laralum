<?php

namespace App\Http\Controllers\Laralum;

use Request;
use Auth;
use Storage;
use Schema;
use Mail;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use App\Users_Settings;
use App\Role;
use App\Permission;
use App\Blog;
use App\Post;
use App\Post_Comment;
use App\Post_View;
use App\Settings;
use App\Document;
use Location;
use URL;

/**
 * The Laralum class
 *
 * The main Laralum class contains diferent elements to help you develop faster
 *
 */

class Laralum extends Controller
{
    public static function version()
    {
        return Laralum::settings()->laralum_version;
    }

    public static function websiteTitle()
    {
        return Laralum::settings()->website_title;
    }

    public static function users($type = null, $data = null)
    {
        if($type and $data) {
            return User::where($type, $data)->get();
        }
        return User::all();
    }

    public static function roles($type = null, $data = null)
    {
        if($type and $data) {
            return Role::where($type, $data)->get();
        }
        return Role::all();
    }

    public static function permissions($type = null, $data = null)
    {
        if($type and $data) {
            return Permission::where($type, $data)->get();
        }
        return Permission::all();
    }

    public static function blogs($type = null, $data = null)
    {
        if($type and $data) {
            return Blog::where($type, $data)->get();
        }
        return Blog::all();
    }

    public static function posts($type = null, $data = null)
    {
        if($type and $data) {
            return Post::where($type, $data)->get();
        }
        return Post::all();
    }

    public static function postViews($type = null, $data = null)
    {
        if($type and $data) {
            return Post_View::where($type, $data)->get();
        }
        return Post_View::all();
    }

    public static function comments($type = null, $data = null)
    {
        if($type and $data) {
            return Post_Comment::where($type, $data)->get();
        }
        return Post_Comment::all();
    }

    public static function documents($type = null, $data = null)
    {
        if($type and $data) {
            return Document::where($type, $data)->get();
        }
        return Document::all();
    }

    public static function user($type, $data)
    {
        if($type == 'id') {
            return User::findOrFail($data);
        }
        return User::where($type, $data)->first();
    }

    public static function role($type, $data)
    {
        if($type == 'id') {
            return Role::findOrFail($data);
        }
        return Role::where($type, $data)->first();
    }

    public static function permission($type, $data)
    {
        if($type == 'id') {
            return Permission::findOrFail($data);
        }
        return Permission::where($type, $data)->first();
    }

    public static function blog($type, $data)
    {
        if($type == 'id') {
            return Blog::findOrFail($data);
        }
        return Blog::where($type, $data)->first();
    }

    public static function post($type, $data)
    {
        if($type == 'id') {
            return Post::findOrFail($data);
        }
        return Post::where($type, $data)->first();
    }

    public static function postView($type, $data)
    {
        if($type == 'id') {
            return Post_View::findOrFail($data);
        }
        return Post_View::where($type, $data)->first();
    }

    public static function comment($type, $data)
    {
        if($type == 'id') {
            return Post_Comment::findOrFail($data);
        }
        return Post_Comment::where($type, $data)->first();
    }

    public static function document($type, $data)
    {
        if($type == 'id') {
            return Document::findOrFail($data);
        }
        return Document::where($type, $data)->first();
    }

    public static function newUser()
    {
        return new User;
    }

    public static function newRole()
    {
        return new Role;
    }

    public static function newPermission()
    {
        return new Permission;
    }

    public static function newBlog()
    {
        return new Blog;
    }

    public static function newPost()
    {
        return new Post;
    }

    public static function newPostView()
    {
        return new Post_View;
    }

    public static function newComment()
    {
        return new Post_Comment;
    }

    public static function newDocument()
    {
        return new Document;
    }

    public static function settings()
    {
        return Settings::first();
    }

    public static function userSettings()
    {
        return Users_Settings::first();
    }

    public static function defaultRole()
    {
        return Laralum::role('id', Laralum::userSettings()->default_role);
    }

    public static function getIP()
    {
        # Get Real IP
        if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
        {
          $ip=$_SERVER['HTTP_CLIENT_IP'];
        }
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
        {
          $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        else
        {
          $ip=$_SERVER['REMOTE_ADDR'];
        }

        return $ip;
    }

    public static function permissionToAccess($slug)
    {
        if(!Laralum::loggedInUser()->hasPermission($slug)) {
            abort(401, "You don't have permissions to access this area");
        }
    }

    public static function files()
    {
        $files = Storage::files();
        $ignore = ['.gitignore'];
        $final_files = [];
        foreach($files as $file) {
            $add = true;
            foreach($ignore as $ign){
                if($ign == $file) {
                    $add = false;
                }
            }
            if($add) {
                array_push($final_files, $file);
            }
        }
        $files = $final_files;

        return $files;
    }

    public static function isDocument($file_name)
    {
        if(Laralum::document('name', $file_name)) {
            return true;
        } else {
            return false;
        }
    }

    public static function addDownload($file_name)
    {
        if(Laralum::isDocument($file_name)) {
            $file = Document::where('name', $file_name)->first();
            $file->downloads = $file->downloads + 1;
            $file->save();
        }
    }

    public static function downloadLink($file_name)
    {
        $link = url('/');
        if(Laralum::isDocument($file_name)) {
            $document = Document::where('name', $file_name)->first();
            $link = route('Laralum::document_downloader', ['slug' => $document->slug]);
        }
        return $link;
    }

    public static function downloadFile($file_name)
    {
        # Add a new download to the file if it's a document
        if(Laralum::isDocument($file_name)) {
            Laralum::addDownload($file_name);
        }
        return response()->download(storage_path('app/' . $file_name));
    }

    public static function isFile($file_name)
    {
        $files = Laralum::files();
        if(in_array($file_name, $files)) {
            return true;
        } else {
            return false;
        }
    }

    public static function mustBeFile($file_name)
    {
        if(!Laralum::isFile($file_name)) {
            abort(404);
        }
    }

    public static function fileExtension($file_name)
    {
        return pathinfo($file_name, PATHINFO_EXTENSION);
    }

    public static function imageFormats()
    {
        return ['png', 'jpg', 'jpeg', 'gif', 'bmp'];
    }

    public static function checkInstalled()
    {
        if(env('LARALUM_INSTALLED', false)){
            return true;
        }
        return false;
    }

    public static function checkDocumentOwner($type, $data){
        if(Auth::user()->id == Laralum::document($type, $data)->author->id) {
            return true;
        } else {
            return false;
        }
    }

    public static function deleteFile($file_name)
    {
        Laralum::mustBeFile($file_name);

        Storage::delete($file_name);
    }

    public static function loggedInUser() {
        return Auth::user();
    }

    public static function noFlags()
    {
        return ['FF', 'BL', 'BQ', 'JE', 'GG', 'MF', 'XK', 'CW', 'SX', 'SS', 'AQ'];
    }

    public static function countryCodeField()
    {
        return 'country_code';
    }

    public static function allowEditingField()
    {
        return 'allow_editing';
    }

    public static function dropdown($slug, $array = null)
    {
        require('Data/Dropdowns.php');
        if($array) {
            return $dropdowns[$slug];
        } else {
            $dropdowns_object = [];
            foreach($dropdowns[$slug] as $drop) {
                array_push($dropdowns_object, Laralum::rettype($drop, 'object'));
            }
            return $dropdowns_object;
        }
    }

    public static function checkValueInRelation($data, $value, $value_index)
    {
        foreach($data as $dta) {
            if($dta->$value_index == $value) {
                return true;
            }
        }
        return false;
    }

    public static function rettype($mixed, $type = NULL) {
        $type === NULL || settype($mixed, $type);
        return $mixed;
    }

    public static function getCountryCode($ip)
    {
        return Location::get($ip)->countryCode;
    }

    public static function mustBeAdmin($user)
    {
        if(!Laralum::isAdmin($user)) {
            abort(403, trans('laralum.error_must_be_admin'));
        }
    }

    public static function mustNotBeAdmin($user)
    {
        if(Laralum::isAdmin($user) and !Laralum::loggedInUser()->su) {
            abort(403, trans('laralum.error_no_rights_against_admin'));
        }
    }

    public static function isAdmin($user)
    {
        return $user->isAdmin();
    }

    public static function permissionName($slug)
    {
        $perm_file = 'permissions';
        $trans = trans($perm_file.'.'.$slug);
        if($perm_file.'.'.$slug == $trans) {
            return $slug;
        } else {
            return $trans;
        }
    }

    public static function permissionDescription($slug)
    {
        $perm_file = 'permissions';
        $trans = trans($perm_file.'.'.$slug.'_desc');
        $slug = $slug . '_desc';
        if($perm_file.'.'.$slug == $trans) {
            return "No description";
        } else {
            return $trans;
        }
    }

    /**
     * Formats a string date into a fancy, human readable date
     *
     * Returns a date formatted for human readable
     *
     * @param string $date_string The date in string format
     *
     * @return date
     */

    public static function fancyDate($date_string)
    {
        return date('F j, Y, g:i A',strtotime($date_string));
    }

    /**
     * Returns the default avatar URL
     *
     * When using this function it will return the default avatar URL
     *
     *
     * @return url
     */

    public static function defaultAvatar()
    {
        return asset(Laralum::publicPath() . '/images/avatar.jpg');
    }

    public static function mustHaveBlog($blog_id)
    {
        if(!Laralum::loggedInUser()->su){
            if(!Laralum::loggedInUser()->has_blog($blog_id) and !Laralum::loggedInUser()->owns_blog($blog_id)){
                abort(403, trans('laralum.error_not_allowed'));
            }
        }
    }

    public static function mustOwnBlog($blog_id)
    {
        if(!Laralum::loggedInUser()->su){
            if(!Laralum::loggedInUser()->owns_blog($blog_id)){
                abort(403, trans('laralum.error_not_allowed'));
            }
        }
    }

    public static function pieChart($title, $labels, $data, $colors = null)
    {
        $id = Laralum::randomString();

        if(Laralum::settings()->pie_chart_source == 'chartjs') {
            $graph = "
            <canvas id='$id'></canvas>
            <script>
                var ctx = document.getElementById('$id');
                var myChart = new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: ["; foreach($labels as $label){$graph .= "'" . $label . "',";} $graph .="],
                        datasets: [{
                            data: ["; foreach($data as $dta){$graph .= $dta . ',';} $graph .="],
                            backgroundColor: [";
                            if($colors){
                                foreach($colors as $color){
                                    $graph .= "'" . $color . "',";
                                }
                            } else {
                                foreach($data as $dta){
                                    $graph .= "'" . Laralum::randomColor() . "',";
                                }
                            }
                            $graph .="],
                        }]
                    },
                    options: {
                        title: {
                            display: true,
                            text: '". $title ."',
                            fontSize: 20,
                        }
                    }
                });
            </script>
            ";
        } elseif(Laralum::settings()->pie_chart_source == 'highcharts') {
            $graph = "
                <script type='text/javascript'>
                    $(function () {
                        $('#$id').highcharts({
                            chart: {
                                plotBackgroundColor: null,
                                plotBorderWidth: null,
                                plotShadow: false,
                                type: 'pie'
                            },
                            title: {
                                text: '$title'
                            },
                            tooltip: {
                                pointFormat: '{point.y} <b>({point.percentage:.1f}%)</b>'
                            },
                            plotOptions: {
                                pie: {
                                    allowPointSelect: true,
                                    cursor: 'pointer',
                                    dataLabels: {
                                        enabled: true,
                                        format: '<b>{point.name}</b>: {point.y} ({point.percentage:.1f}%)',
                                        style: {
                                            color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                                        }
                                    }
                                }
                            },
                            series: [{
                                colorByPoint: true,
                                data: [
                                ";
                                $i = 0;
                                foreach($data as $dta){
                                    $e = $labels[$i];
                                    $v = $dta;
                                    $graph .= "{name: '$e', y: $v},";
                                    $i++;
                                }
                                $graph .="
                                ]
                            }]
                        });
                    });
                </script>
                <div id='$id'></div>
            ";
        } else {
            $graph = "
                <script type='text/javascript'>
                  google.charts.setOnLoadCallback(drawPieChart);
                  function drawPieChart() {

                    var data = google.visualization.arrayToDataTable([
                      ['Element', 'Value'],
                      ";
                        $i = 0;
                        foreach($data as $dta){
                            $e = $labels[$i];
                            $v = $dta;
                            $graph .= "['$e', $v],";
                            $i++;
                        }
                        $graph .="
                    ]);

                    var options = {
                        fontSize: 12,
                        title: '$title',";
                        if($colors){
                            $graph .= "colors:[";
                            foreach($colors as $color){
                                $graph .= "'$color',";
                            }
                            $graph .= "],";
                        }
                    $graph .= "
                    };

                    var chart = new google.visualization.PieChart(document.getElementById('$id'));

                    chart.draw(data, options);
                  }
                </script>

                <div id='$id'></div>
            ";
        }
        return $graph;
    }

    public static function barChart($title, $element_label, $labels, $data, $colors = null)
    {
        $id = Laralum::randomString();

        if(Laralum::settings()->bar_chart_source == 'chartjs') {
            $graph = "
            <canvas id='$id'></canvas>
            <script>
                var ctx = document.getElementById('$id');
                var myChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: ["; foreach($labels as $label){$graph .= "'" . $label . "',";} $graph .="],
                        datasets: [
                            {
                                label: '$element_label',
                                backgroundColor: [";
                                if($colors){
                                    foreach($colors as $color){
                                        $graph .= "'" . $color . "',";
                                    }
                                } else {
                                    foreach($data as $dta){
                                        $graph .= "'" . Laralum::randomColor() . "',";
                                    }
                                }
                                $graph .="],
                                data: ["; foreach($data as $dta){$graph .= $dta . ',';} $graph .="],
                            }
                        ]
                    },
                    options: {
                        title: {
                            display: true,
                            text: '". $title ."',
                            fontSize: 20,
                        },
                        scales: {
                            yAxes: [{
                                display: true,
                                ticks: {
                                    beginAtZero: true,
                                }
                            }]
                        }
                    }
                });
            </script>
            ";
        } elseif(Laralum::settings()->bar_chart_source == 'highcharts') {
            $graph = "
                <script type='text/javascript'>
                    $(function () {
                        $('#$id').highcharts({
                            chart: {
                                plotBackgroundColor: null,
                                plotBorderWidth: null,
                                plotShadow: false,
                                type: 'column'
                            },
                            title: {
                                text: '$title'
                            },
                            plotOptions: {
                               column: {
                                   pointPadding: 0.2,
                                   borderWidth: 0
                               }
                           },
                           xAxis: {
                                categories: [
                                    ";
                                    foreach($labels as $label){
                                        $graph .= "'$label',";
                                    }
                                    $graph .="
                                ],
                                crosshair: true
                            },
                            series: [{
                                name: '$element_label',
                                data: [
                                ";
                                foreach($data as $dta){
                                    $graph .= "$dta,";
                                }
                                $graph .="
                                ]
                            }]
                        });
                    });
                </script>
                <div id='$id'></div>
            ";
        } else {
            $graph = "
                <script type='text/javascript'>
                  google.charts.setOnLoadCallback(drawPieChart);
                  function drawPieChart() {

                      var data = google.visualization.arrayToDataTable([
                          ['Element', '$element_label'],
                          ";
                            $i = 0;
                            foreach($data as $dta){
                                $e = $labels[$i];
                                $v = $dta;
                                $graph .= "['$e', $v],";
                                $i++;
                            }
                            $graph .="
                      ]);

                    var options = {
                        legend: { position: 'top', alignment: 'end' },
                        fontSize: 12,
                        title: '$title',";
                        if($colors){
                            $graph .= "colors:[";
                            foreach($colors as $color){
                                $graph .= "'$color',";
                            }
                            $graph .= "],";
                        }
                    $graph .= "
                    };

                    var chart = new google.visualization.ColumnChart(document.getElementById('$id'));

                    chart.draw(data, options);
                  }
                </script>

                <div id='$id'></div>
            ";
        }
        return $graph;
    }

    public static function lineChart($title, $element_label, $labels, $data, $color = null)
    {
        $id = Laralum::randomString();
        $default_color = Laralum::settings()->header_color;

        if(Laralum::settings()->line_chart_source == 'chartjs') {
            $graph = "
            <canvas id='$id'></canvas>
            <script>
            	var ctx = document.getElementById('$id');
            	var data = {
            	    labels: ["; foreach($labels as $label){$graph .= "'" . $label . "',";} $graph .="],
            	    datasets: [
            	        {
            	            label: '$element_label',
            	            lineTension: 0.3,
                            "; if($color){ $graph .= "borderColor: '$color',"; }else{ $graph .= "borderColor: '$default_color',"; } $graph .= "
            	            data: ["; foreach($data as $dta){$graph .= $dta . ',';} $graph .="],
            	        }
            	    ]
            	};

            	var myLineChart = new Chart(ctx, {
            		type: 'line',
            		data: data,
            		options: {
            			title: {
            	            display: true,
                            text: '$title',
            				fontSize: 20,
            	        }
            	    }
            	});
            </script>
            ";
        } elseif(Laralum::settings()->line_chart_source == 'highcharts') {
            $graph = "
            <script type='text/javascript'>
                $(function () {
                    $('#$id').highcharts({
                        title: {
                            text: '$title',
                            x: -20 //center
                        },
                        xAxis: {
                            categories: ["; foreach($labels as $label){$graph .= "'" . $label . "',";} $graph .="]
                        },
                        yAxis: {
                            title: {
                                text: '$element_label'
                            },
                            plotLines: [{
                                value: 0,
                                height: 0.5,
                                width: 1,
                                color: '#808080'
                            }]
                        },
                        plotOptions: {
                            series: {
                                color: '"; if($color){ $graph .= $color; }else{ $graph .= $default_color; } $graph .= "'
                            }
                        },
                        legend: {
                            layout: 'vertical',
                            align: 'right',
                            verticalAlign: 'middle',
                            borderWidth: 0
                        },
                        series: [{
                            name: '$element_label',
                            data: ["; foreach($data as $dta){$graph .= $dta . ',';} $graph .="]
                        }]
                    });
                });
                </script>
                <div id='$id'></div>
            ";
        } else {
            $graph = "
                <script type='text/javascript'>

                google.charts.setOnLoadCallback(drawChart);

                function drawChart() {
                    var data = google.visualization.arrayToDataTable([
                        ['Element', '$element_label'],
                        ";
                          $i = 0;
                          foreach($data as $dta){
                              $e = $labels[$i];
                              $v = $dta;
                              $graph .= "['$e', $v],";
                              $i++;
                          }
                          $graph .="
                    ]);

                    var options = {
                        fontSize: 12,
                        title: '$title',
                        "; if($color){ $graph .= "colors: ['$color'],"; }else{$graph .= "colors: ['$default_color'],";} $graph .= "
                        legend: { position: 'top', alignment: 'end' }
                    };

                    var chart = new google.visualization.LineChart(document.getElementById('$id'));

                    chart.draw(data, options);
                }
                </script>

                <div id='$id'></div>
            ";
        }
        return $graph;
    }

    public static function geoChart($title, $element_label, $data)
    {
        $id = Laralum::randomString();
        $countries = Laralum::countries();
        $default_color = Laralum::settings()->header_color;

        // Get the max / min index
        $max = 0;
        $min = $data[0][1];
        foreach($data as $dta) {
            if($dta[1] > $max) {
                $max = $dta[1];
            } elseif($dta[1] < $min) {
                $min = $dta[1];
            }
        }

        if(Laralum::settings()->geo_chart_source == 'highcharts') {
            $graph = "
                <script type='text/javascript'>
                    $(function () {

                        // Initiate the chart
                        $('#$id').highcharts('Map', {

                            title : {
                                text : '$title'
                            },

                            mapNavigation: {
                                enabled: true,
                                enableDoubleClickZoomTo: true
                            },

                            colorAxis: {
                                min: $min,
                                minColor: '#E0E0E0',
                                max: $max,
                                maxColor: '$default_color',
                            },

                            series : [{
                                data : [";
                                  foreach($data as $dta){
                                      $e = $dta[0];
                                      $v = $dta[1];
                                      $graph .= "{'code': '$e', 'value': $v},";
                                  }
                                  $graph .="
                                ],
                                mapData: Highcharts.maps['custom/world'],
                                joinBy: ['iso-a2', 'code'],
                                name: '$element_label',
                                states: {
                                    hover: {
                                        color: '#BADA55'
                                    }
                                },
                            }]
                        });
                    });
                </script>
                <div id='$id'></div>
            ";
        } else {
            $graph = "
                <script type='text/javascript'>
                  google.charts.setOnLoadCallback(drawRegionsMap);

                  function drawRegionsMap() {

                    var data = google.visualization.arrayToDataTable([
                        ['Country', '$element_label'],
                      ";
                        foreach($data as $dta){
                            $e = $countries[$dta[0]];
                            $v = $dta[1];
                            $graph .= "['$e', $v],";
                        }
                        $graph .="
                    ]);

                    var options = {
                      colorAxis: {colors: ['#E0E0E0', '$default_color']},
                      datalessRegionColor: '#e0e0e0',
                      defaultColor: '#e0e0e0',
                    };

                    var chart = new google.visualization.GeoChart(document.getElementById('$id'));

                    chart.draw(data, options);
                  }
                </script>
                <center><b style='font-size: 18px;'>$title</b><br><br></center>
                <div id='$id'></div>
            ";
        }
        return $graph;
    }

    public static function randomString($length = 10) {
        return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
    }

    public static function randomColor()
    {
        return sprintf('#%06X', mt_rand(0, 0xFFFFFF));
    }

    public static function currentURL()
    {
        return Request::url();
    }

    public static function previousURL()
    {
        return URL::previous();
    }

    public static function include($name)
    {
        require('Data/Includes.php');
        if($includes){
            return $includes[$name];
        }
    }

    public static function publicPath()
    {
        return 'laralum_public';
    }

    public static function countries()
    {
        $json = file_get_contents(Laralum::publicPath() . '/assets/countries/names.json');
        return json_decode($json, true);
    }

    public static function widget($name)
    {
        require('Data/Widgets.php');
        if($widgets and array_key_exists($name, $widgets)){
            return $widgets[$name];
        } else {
            return "<span style='color:red;'>ERROR: </span> Unknown widget";
        }
    }

    public static function laralumLogo()
    {
        return asset(Laralum::publicPath() . '/images/logo-text.png');
    }

    public static function locales()
    {
        require('Data/Locales.php');
        if($locales){
            return $locales;
        }
    }

    public static function dataPath()
    {
        return app_path() . '/Http/Controllers/Laralum/Data';
    }


}
