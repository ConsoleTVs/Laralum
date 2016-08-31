@extends('layouts.admin.panel')
@section('breadcrumb')
    <div class="ui breadcrumb">
        <a class="section" href="{{ route('Laralum::blogs') }}">{{ trans('laralum.blogs_title') }}</a>
        <i class="right angle icon divider"></i>
        <a class="section" href="{{ route('Laralum::blogs_posts', ['id' => $post->blog->id]) }}">{{ trans('laralum.blogs_posts_title') }}</a>
        <i class="right angle icon divider"></i>
        <div class="active section">{{  trans('laralum.posts_graphics_title') }}</div>
    </div>
@endsection
@section('title', trans('laralum.posts_graphics_title'))
@section('icon', "line chart")
@section('subtitle', trans('laralum.posts_graphics_subtitle', ['title' => $post->title]))
@section('content')
<div class="ui doubling stackable two columns grid container">
    <div class="column">
        <div class="ui padded segment">
            <?php

                $labels = array_reverse([
                    date("F j, Y"),
                    date("F j, Y", strtotime("-1 Day")),
                    date("F j, Y", strtotime("-2 Day")),
                    date("F j, Y", strtotime("-3 Day")),
                    date("F j, Y", strtotime("-4 Day")),
                ]);
                $data = array_reverse([
                    count($post->views()->whereDate('created_at', '=', date("Y-m-d"))->get()),
            		count($post->views()->whereDate('created_at', '=', date("Y-m-d", strtotime("-1 Day")))->get()),
            		count($post->views()->whereDate('created_at', '=', date("Y-m-d", strtotime("-2 Day")))->get()),
            		count($post->views()->whereDate('created_at', '=', date("Y-m-d", strtotime("-3 Day")))->get()),
            		count($post->views()->whereDate('created_at', '=', date("Y-m-d", strtotime("-4 Day")))->get()),
                ]);
                $element_label = trans('laralum.total_views');
                $title = trans('laralum.posts_graph1');
            ?>
            {!! Laralum::lineChart($title, $element_label, $labels, $data) !!}
        </div>
    </div>
    <div class="column">
        <div class="ui padded segment">
            <?php
                $g_labels = [];
                foreach($post->views as $view){
                    $add = true;
                    foreach($g_labels as $g_label) {
                        if($g_label == $view->country_code){
                            $add = false;
                        }
                    }
                    if($add) {
                        array_push($g_labels,$view->country_code);
                    }
                }

                $countries = Laralum::countries();

                $title = trans('laralum.posts_graph2');
                $labels = [];
                $data = [];
                foreach($g_labels as $g_label){
                    array_push($labels, $countries[$g_label]);
                    array_push($data, count(App\Post_View::whereCountry_codeAndPost_id($g_label, $post->id)->get()) );
                }
            ?>
            {!! Laralum::pieChart($title, $labels, $data) !!}
        </div>
    </div>
</div>
<div class="ui doubling stackable one columns grid container">
    <div class="column">
        <div class="ui padded segment">
            <center>
                <?php
                    $title = trans('laralum.posts_graph2');
                    $element_label = 'Views';
                    $data = [];
                    foreach($g_labels as $g_label){
                        array_push($data, [$g_label, count(App\Post_View::whereCountry_codeAndPost_id($g_label, $post->id)->get())]);
                    }
                ?>
                {!! Laralum::geoChart($title, $element_label, $data) !!}
            </center>
        </div><br>
    </div>
</div>
@endsection
