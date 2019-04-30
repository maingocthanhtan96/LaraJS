<?php

use Illuminate\Http\Request;

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
	Route::post('/login', 'Api\v1\AuthController@login')->name('login');
	Route::post('/register', 'Api\v1\AuthController@register')->name('register');

	Route::group(['middleware' => 'auth:api', 'namespace' => 'Api\v1'], function () {
		Route::get('/user', 'UserController@userInfo');
		Route::get('/logout', 'AuthController@logout')->name('logout');


		Route::group(['prefix' => 'users'], function () {
			Route::get('/list', 'UserController@list');
			Route::get('/roles', 'UserController@roles');
			Route::match(['post','put'], '/storeOrUpdate/{id?}', 'UserController@storeOrUpdate');
			Route::get('/edit/{id}', 'UserController@edit');
			Route::delete('/delete/{id}', 'UserController@delete');
		});
	});
});
