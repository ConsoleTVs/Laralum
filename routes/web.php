<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::group(['middleware' => 'laralum.base'], function () {

	/*
	|--------------------------------------------------------------------------
	| Add your website routes here
	|--------------------------------------------------------------------------
	|
	| The laralum.base middleware will be applied
	|
	*/

	# Welcome route
	Route::get('/', function () {
	    return view('welcome');
	});

    # Auth Route
    Auth::routes();
});

Route::group(['middleware' => ['auth', 'laralum.base']], function () {

	/*
	|--------------------------------------------------------------------------
	| Add your website routes here (users are forced to login to access those)
	|--------------------------------------------------------------------------
	|
	| The laralum.base and auth middlewares will be applied
	|
	*/

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

Route::group(['middleware' => ['auth', 'laralum.base'], 'as' => 'Laralum::'], function () {

	Route::get('activate/{token?}', 'Auth\ActivationController@activate')->name('activate_account');
    Route::post('activate', 'Auth\ActivationController@activateWithForm')->name('activate_form');
    Route::get('/banned', function() {
        return view('auth/banned');
    })->name('banned');

});

Route::group(['middleware' => ['laralum.base'], 'as' => 'Laralum::'], function () {

	# Public document downloads
	Route::get('/document/{slug}', 'Laralum\DownloadsController@downloader')->name('document_downloader');
	Route::post('/document/{slug}', 'Laralum\DownloadsController@download');

	# Public language changer
	Route::get('/locale/{locale}', 'Laralum\LocaleController@set')->name('locale');

});

Route::group(['middleware' => ['laralum.base'], 'prefix' => 'admin', 'namespace' => 'Laralum', 'as' => 'Laralum::'], function () {

	# Public document downloads
	Route::get('/install', 'InstallerController@locale')->name('install_locale');
	Route::get('/install/{locale}', 'InstallerController@show')->name('install');
	Route::post('/install/{locale}', 'InstallerController@installConfig');
	Route::get('/install/{locale}/confirm', 'InstallerController@install')->name('install_confirm');

});

Route::group(['middleware' => ['auth', 'laralum.base', 'laralum.auth'], 'prefix' => 'admin', 'namespace' => 'Laralum', 'as' => 'Laralum::'], function () {

	# Home Controller
    Route::get('/', 'DashboardController@index')->name('dashboard');

    # Users Routes
    Route::get('/users', 'UsersController@index')->name('users');

    Route::get('/users/create', 'UsersController@create')->name('users_create');
    Route::post('/users/create', 'UsersController@store');

    Route::get('/users/settings', 'UsersController@editSettings')->name('users_settings');
    Route::post('/users/settings', 'UsersController@updateSettings');

    Route::get('/users/{id}', 'UsersController@show')->name('users_profile');

	Route::get('/users/{id}/edit', 'UsersController@edit')->name('users_edit');
    Route::post('/users/{id}/edit', 'UsersController@update');

    Route::get('/users/{id}/roles', 'UsersController@editRoles')->name('users_roles');
    Route::post('/users/{id}/roles', 'UsersController@setRoles');

    Route::get('/users/{id}/delete', 'SecurityController@confirm')->name('users_delete');
    Route::post('/users/{id}/delete', 'UsersController@destroy');


    # Roles Routes
    Route::get('/roles', 'RolesController@index')->name('roles');

    Route::get('/roles/create', 'RolesController@create')->name('roles_create');
    Route::post('/roles/create', 'RolesController@store');

    Route::get('/roles/{id}', 'RolesController@show')->name('roles_show');

    Route::get('/roles/{id}/edit', 'RolesController@edit')->name('roles_edit');
    Route::post('/roles/{id}/edit', 'RolesController@update');

    Route::get('/roles/{id}/permissions', 'RolesController@editPermissions')->name('roles_permissions');
    Route::post('/roles/{id}/permissions', 'RolesController@setPermissions');

    Route::get('/roles/{id}/delete', 'SecurityController@confirm')->name('roles_delete');
    Route::post('/roles/{id}/delete', 'RolesController@destroy');


    # Permissions Routes
    Route::get('/permissions', 'PermissionsController@index')->name('permissions');

    Route::get('/permissions/create', 'PermissionsController@create')->name('permissions_create');
    Route::post('/permissions/create', 'PermissionsController@store');

    Route::get('/permissions/{id}/edit', 'PermissionsController@edit')->name('permissions_edit');
    Route::post('/permissions/{id}/edit', 'PermissionsController@update');

    Route::get('/permissions/{id}/delete', 'SecurityController@confirm')->name('permissions_delete');
    Route::post('/permissions/{id}/delete', 'PermissionsController@destroy');

	# Blogs Routes
	Route::get('/blogs', 'BlogsController@index')->name('blogs');

	Route::get('/blogs/create', 'BlogsController@create')->name('blogs_create');
	Route::post('/blogs/create', 'BlogsController@store');

	Route::get('/blogs/{id}', 'BlogsController@posts')->name('blogs_posts');

	Route::get('/blogs/{id}/edit', 'BlogsController@edit')->name('blogs_edit');
	Route::post('/blogs/{id}/edit', 'BlogsController@update');

	Route::get('/blogs/{id}/roles', 'BlogsController@roles')->name('blogs_roles');
	Route::post('/blogs/{id}/roles', 'BlogsController@updateRoles');

	Route::get('/blogs/{id}/delete', 'SecurityController@confirm')->name('blogs_delete');
	Route::post('/blogs/{id}/delete', 'BlogsController@destroy');

	# Posts Routes
	Route::get('/posts/{id}', 'PostsController@index')->name('posts');

	Route::get('/posts/create/{id}', 'PostsController@create')->name('posts_create');
	Route::post('/posts/create/{id}', 'PostsController@store');

	Route::get('/posts/{id}/edit', 'PostsController@edit')->name('posts_edit');
	Route::post('/posts/{id}/edit', 'PostsController@update');

	Route::get('/posts/{id}/graphics', 'PostsController@graphics')->name('posts_graphics');

	Route::get('/posts/{id}/delete', 'SecurityController@confirm')->name('posts_delete');
	Route::post('/posts/{id}/delete', 'PostsController@destroy');

	# Comments Routes
	Route::post('/comments/create/{id}', 'CommentsController@create')->name('comments_create');

	Route::get('/comments/{id}/edit', 'CommentsController@edit')->name('comments_edit');
	Route::post('/comments/{id}/edit', 'CommentsController@update');

	Route::get('/comments/{id}/delete', 'SecurityController@confirm')->name('comments_delete');
	Route::post('/comments/{id}/delete', 'CommentsController@destroy');


	# Database CRUD
	Route::get('/CRUD', 'CRUDController@index')->name('CRUD');

	Route::get('/CRUD/{table}', 'CRUDController@table')->name('CRUD_table');

	Route::get('/CRUD/{table}/create', 'CRUDController@create')->name('CRUD_create');
	Route::post('/CRUD/{table}/create', 'CRUDController@createRow');

	Route::get('/CRUD/{table}/{id}', 'CRUDController@row')->name('CRUD_edit');
	Route::post('/CRUD/{table}/{id}', 'CRUDController@saveRow');

	Route::get('/CRUD/{table}/{id}/delete', 'SecurityController@confirm')->name('CRUD_delete');
	Route::post('/CRUD/{table}/{id}/delete', 'CRUDController@deleteRow');

	# File Manager
	Route::get('/files', 'FilesController@files')->name('files');

	Route::get('/files/upload', 'FilesController@showUpload')->name('files_upload');
	Route::post('/files/upload', 'FilesController@upload');

	Route::get('/documents/{file}/create', 'DocumentsController@showCreate')->name('documents_create');
	Route::post('/documents/{file}/create', 'DocumentsController@createDocument');

	Route::get('/documents/{slug}/edit', 'DocumentsController@edit')->name('documents_edit');
	Route::post('/documents/{slug}/edit', 'DocumentsController@update');

	Route::get('/documents/{slug}/delete', 'SecurityController@confirm')->name('documents_delete');
	Route::post('/documents/{slug}/delete', 'DocumentsController@delete');

	Route::get('/files/{file}/delete', 'SecurityController@confirm')->name('files_delete');
	Route::post('/files/{file}/delete', 'FilesController@delete');

	Route::get('/files/{file}/download', 'FilesController@fileDownload')->name('files_download');

	# Settings
	Route::get('/settings', 'SettingsController@edit')->name('settings');
	Route::post('/settings', 'SettingsController@update');

	# About
    Route::get('/about', 'AboutController@index')->name('about');

});
