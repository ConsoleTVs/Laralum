<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/


/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/


Route::group(['middleware' => 'web'], function () {

	# Welcome route
	Route::get('/', function () {
	    return view('welcome');
	});

	# Auth routes
    Route::auth();

	# Default home route
    Route::get('/home', 'HomeController@index');
});

























/*
+---------------------------------------------------------------------------+
| Laralum Routes															|
+---------------------------------------------------------------------------+
|  _                     _													|
| | |                   | |													|
| | |     __ _ _ __ __ _| |_   _ _ __ ___									|
| | |    / _` | '__/ _` | | | | | '_ ` _ \									|
| | |___| (_| | | | (_| | | |_| | | | | | |									|
| \_____/\__,_|_|  \__,_|_|\__,_|_| |_| |_| Administration Panel			|
|																			|
+---------------------------------------------------------------------------+
|																			|
| This route group applies the "web" middleware group to every route		|
| it contains. The "web" middleware group is defined in your HTTP			|
| kernel and includes session state, CSRF protection, and more.				|
| This routes are made to manage laralum administration panel, please		|
| don't change anything unless you know what you're doing.					|
|																			|
+---------------------------------------------------------------------------+
*/

Route::group(['middleware' => ['web', 'auth']], function () {

	Route::get('activate/{token?}', 'Auth\ActivationController@activate');
    Route::post('activate', 'Auth\ActivationController@activateWithForm');
    Route::get('/banned', function() {
        return view('auth/banned');
    });
});

Route::group(['middleware' => ['web', 'auth', 'admin.auth'], 'prefix' => 'admin', 'namespace' => 'Admin'], function () {

	# Home Controller
    Route::get('/', 'HomeController@index');

    # Users Routes
    Route::get('/users', 'UsersController@index');

    Route::get('/users/create', 'UsersController@create');
    Route::post('/users/create', 'UsersController@store');

    Route::get('/users/settings', 'UsersController@editSettings');
    Route::post('/users/settings', 'UsersController@updateSettings');

    Route::get('/users/{id}', 'UsersController@show');

	Route::get('/users/{id}/edit', 'UsersController@edit');
    Route::post('/users/{id}/edit', 'UsersController@update');

    Route::get('/users/{id}/roles', 'UsersController@editRoles');
    Route::post('/users/{id}/roles', 'UsersController@setRoles');

    Route::get('/users/{id}/delete', 'SecurityController@confirm');
    Route::post('/users/{id}/delete', 'UsersController@destroy');


    # Roles Routes
    Route::get('/roles', 'RolesController@index');

    Route::get('/roles/create', 'RolesController@create');
    Route::post('/roles/create', 'RolesController@store');

    Route::get('/roles/settings', 'RolesController@editSettings');
    Route::post('/roles/settings', 'RolesController@updateSettings');

    Route::get('/roles/{id}', 'RolesController@show');

    Route::get('/roles/{id}/edit', 'RolesController@edit');
    Route::post('/roles/{id}/edit', 'RolesController@update');

    Route::get('/roles/{id}/permissions', 'RolesController@editPermissions');
    Route::post('/roles/{id}/permissions', 'RolesController@setPermissions');

    Route::get('/roles/{id}/delete', 'SecurityController@confirm');
    Route::post('/roles/{id}/delete', 'RolesController@destroy');


    # Permissions Routes
    Route::get('/permissions', 'PermissionsController@index');

    Route::get('/permissions/create', 'PermissionsController@create');
    Route::post('/permissions/create', 'PermissionsController@store');

    Route::get('/permissions/{id}', 'PermissionsController@show');

    Route::get('/permissions/{id}/edit', 'PermissionsController@edit');
    Route::post('/permissions/{id}/edit', 'PermissionsController@update');

    Route::get('/permissions/{id}/delete', 'SecurityController@confirm');
    Route::post('/permissions/{id}/delete', 'PermissionsController@destroy');

    # Permission Types Routes
    Route::get('/permissions/types/create', 'PermissionsController@createType');
    Route::post('/permissions/types/create', 'PermissionsController@storeType');

    Route::get('/permissions/types/{id}/edit', 'PermissionsController@editType');
    Route::post('/permissions/types/{id}/edit', 'PermissionsController@updateType');

    Route::get('/permissions/types/{id}/delete', 'SecurityController@confirm');
    Route::post('/permissions/types/{id}/delete', 'PermissionsController@destroyType');

	# Blogs Routes
	Route::get('/blogs', 'BlogsController@index');

	Route::get('/blogs/create', 'BlogsController@create');
	Route::post('/blogs/create', 'BlogsController@store');

	Route::get('/blogs/{id}', 'BlogsController@posts');

	Route::get('/blogs/{id}/edit', 'BlogsController@edit');
	Route::post('/blogs/{id}/edit', 'BlogsController@update');

	Route::get('/blogs/{id}/delete', 'SecurityController@confirm');
	Route::post('/blogs/{id}/delete', 'BlogsController@destroy');

	# Posts Routes
	Route::get('/posts/{id}', 'PostsController@index');

	Route::get('/posts/create/{id}', 'PostsController@create');
	Route::post('/posts/create/{id}', 'PostsController@store');

	Route::get('/posts/{id}/edit', 'PostsController@edit');
	Route::post('/posts/{id}/edit', 'PostsController@update');

	Route::get('/posts/{id}/graphics', 'PostsController@graphics');

	Route::get('/posts/{id}/delete', 'SecurityController@confirm');
	Route::post('/posts/{id}/delete', 'PostsController@destroy');

});
