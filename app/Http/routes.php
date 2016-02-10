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


Route::group(['middleware' => ['web', 'auth']], function () {

	Route::get('activate/{token?}', 'Auth\ActivationController@activate');
    Route::post('activate', 'Auth\ActivationController@activateWithForm');
    Route::get('/banned', function() {
        return view('auth/banned');
    });
});

Route::group(['middleware' => 'web'], function () {

	Route::get('/', function () {
	    return view('welcome');
	});

    Route::get('/test', function() {
        foreach(App\Permission::all() as $perm) {
            $type = $perm->type;
            print str_replace('|', '$',"
                |perm = new Permission;<br>
                |perm->slug = '$perm->slug';<br>
                |perm->name = '$perm->name';<br>
                |perm->info = '$perm->info';<br>
                |perm->type_id = Permission_Types::where('name', '$type->type')->first()->id;<br>
                |perm->su = true;<br>
                |perm->save();<br><br>
            ");
        }
    });
    
    Route::auth();

    Route::get('/home', 'HomeController@index');

    Route::get('/setup', 'Admin\SetupController@index');
    Route::post('/setup', 'Admin\SetupController@setup');
});


Route::group(['middleware' => ['web', 'auth', 'admin.auth'], 'prefix' => 'admin', 'namespace' => 'Admin'], function () {

	#	----------------------
	#	- Admin Panel Routes -
	#	----------------------

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

});