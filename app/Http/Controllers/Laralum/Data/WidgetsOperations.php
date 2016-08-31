<?php

$countries = Laralum::countries();

/*
|--------------------------------------------------------------------------
| latest_users_graph
|--------------------------------------------------------------------------
*/

$latest_users_graph['labels'] = array_reverse([
    date("F j, Y"),
    date("F j, Y", strtotime("-1 Day")),
    date("F j, Y", strtotime("-2 Day")),
    date("F j, Y", strtotime("-3 Day")),
    date("F j, Y", strtotime("-4 Day")),
]);
$latest_users_graph['data'] = array_reverse([
    count(App\User::whereDate('created_at', '=', date("Y-m-d"))->get()),
    count(App\User::whereDate('created_at', '=', date("Y-m-d", strtotime("-1 Day")))->get()),
    count(App\User::whereDate('created_at', '=', date("Y-m-d", strtotime("-2 Day")))->get()),
    count(App\User::whereDate('created_at', '=', date("Y-m-d", strtotime("-3 Day")))->get()),
    count(App\User::whereDate('created_at', '=', date("Y-m-d", strtotime("-4 Day")))->get()),
]);
$latest_users_graph['element_label'] = trans('laralum.users_total_users');
$latest_users_graph['title'] = trans('laralum.users_graph1');

/*
|--------------------------------------------------------------------------
| latest_posts_graph
|--------------------------------------------------------------------------
*/

$latest_posts_graph['labels'] = array_reverse([
    date("F j, Y"),
    date("F j, Y", strtotime("-1 Day")),
    date("F j, Y", strtotime("-2 Day")),
    date("F j, Y", strtotime("-3 Day")),
    date("F j, Y", strtotime("-4 Day")),
]);
$latest_posts_graph['data'] = array_reverse([
    count(App\Post::whereDate('created_at', '=', date("Y-m-d"))->get()),
    count(App\Post::whereDate('created_at', '=', date("Y-m-d", strtotime("-1 Day")))->get()),
    count(App\Post::whereDate('created_at', '=', date("Y-m-d", strtotime("-2 Day")))->get()),
    count(App\Post::whereDate('created_at', '=', date("Y-m-d", strtotime("-3 Day")))->get()),
    count(App\Post::whereDate('created_at', '=', date("Y-m-d", strtotime("-4 Day")))->get()),
]);
$latest_posts_graph['element_label'] = trans('laralum.posts_new_posts');
$latest_posts_graph['title'] = trans('laralum.posts_graph3');

/*
|--------------------------------------------------------------------------
| latest_posts_graph
|--------------------------------------------------------------------------
*/
$roles_users['labels'] = [];
$roles_users['data'] = [];
foreach(Laralum::roles() as $role) {
    array_push($roles_users['labels'], $role->name);
    array_push($roles_users['data'], count($role->users));
}
$roles_users['element_label'] = trans('laralum.users');
$roles_users['title'] = trans('laralum.roles_graph1');

/*
|--------------------------------------------------------------------------
| users_country_pie_graph
|--------------------------------------------------------------------------
*/

$g_labels = [];
foreach(Laralum::users() as $user){
    $add = true;
    foreach($g_labels as $g_label) {
        if($g_label == $user->country_code){
            $add = false;
        }
    }
    if($add) {
        array_push($g_labels,$user->country_code);
    }
}


$users_country_pie_graph['title'] = trans('laralum.users_graph2');
$users_country_pie_graph['labels'] = [];
$users_country_pie_graph['data'] = [];
foreach($g_labels as $g_label){
    array_push($users_country_pie_graph['labels'],$countries[$g_label]);
    array_push($users_country_pie_graph['data'], count(Laralum::users('country_code', $g_label)) );
}

/*
|--------------------------------------------------------------------------
| users_country_geo_graph
|--------------------------------------------------------------------------
*/

$g_labels = [];
foreach(Laralum::users() as $user){
    $add = true;
    foreach($g_labels as $g_label) {
        if($g_label == $user->country_code){
            $add = false;
        }
    }
    if($add) {
        array_push($g_labels,$user->country_code);
    }
}

$users_country_geo_graph['title'] = trans('laralum.users_graph2');
$users_country_geo_graph['element_label'] = trans('laralum.users');
$users_country_geo_graph['data'] = [];
foreach($g_labels as $g_label){
    array_push($users_country_geo_graph['data'], [$g_label, count(Laralum::users('country_code', $g_label))] );
}
