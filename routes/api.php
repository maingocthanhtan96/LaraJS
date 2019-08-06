<?php

use Illuminate\Http\Request;
use \App\Larajs\Permission as LarajsPermission;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::group(['prefix' => 'v1'], function () {
	Route::get('/language/{language}', 'Api\v1\LangController@setLanguage');
	Route::group(['namespace' => 'Api\v1'], function () {
		Route::post('/login', 'AuthController@login')->name('login');
		Route::post('/register', 'AuthController@register')->name('register');
	});

	Route::group(['middleware' => 'auth:api', 'namespace' => 'Api\v1'], function () {
		Route::get('/user', 'UserController@userInfo');
		Route::get('/logout', 'AuthController@logout')->name('logout');
		// FILE
		Route::post('upload-file/store', 'FileController@store');
		Route::get('upload-file/remove', 'FileController@remove');

		// permission Admin
		Route::group(['middleware' => 'permission:'. LarajsPermission::PERMISSION_PERMISSION_MANAGE], function () {
			Route::get('/users/roles/list', 'UserController@getRoles');
			Route::get('/generators/check-model', 'GeneratorController@checkModel');
			Route::get('/generators/check-column', 'GeneratorController@checkColumn');
			Route::get('/generators/get-models', 'GeneratorController@getModels');
			Route::get('/generators/get-columns', 'GeneratorController@getColumns');
			Route::post('/generators/relationship', 'GeneratorController@generateRelationship');
            Route::get('/generators/diagram', 'GeneratorController@generateDiagram');
            Route::apiResource('generators', 'GeneratorController');
            Route::apiResource('users', 'UserController');

            //{{ROUTE_ADMIN_NOT_DELETE_THIS_LINE}}
		});

		// permission User
		Route::group(['middleware' => 'permission:'. LarajsPermission::PERMISSION_USER_MANAGE], function () {
            //{{ROUTE_USER_NOT_DELETE_THIS_LINE}}
		});
	});
});
