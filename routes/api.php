<?php

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
    Route::group(['namespace' => 'Api\v1'], function () {
        Route::get('/language/{language}', 'LangController@setLanguage');
        // Send reset password mail
        Route::post('/forgot-password', 'AuthController@forgotPassword');
        // Handle reset password form process
        Route::post('/reset-password', 'AuthController@callResetPassword');
        // Login
        Route::post('/login', 'AuthController@login')->name('login');

        Route::group(['middleware' => 'auth:api'], function () {
            Route::get('/user-info', 'UserController@userInfo');
            Route::get('/logout', 'AuthController@logout')->name('logout');
            // FILE
            Route::middleware('optimizeImages')->group(function () {
                // all images will be optimized automatically
                Route::post('upload-file/store', 'FileController@store');
                Route::post('upload-file/store-avatar', 'FileController@storeAvatar');
                Route::get('upload-file/remove', 'FileController@remove');
            });

            // permission Admin (Super admin)
            Route::group(['middleware' => 'role:' . LarajsPermission::ROLE_ADMIN], function () {
                Route::group(['prefix' => 'generators'], function () {
                    Route::get('check-model', 'GeneratorController@checkModel');
                    Route::get('check-column', 'GeneratorController@checkColumn');
                    Route::get('get-models', 'GeneratorController@getModels');
                    Route::get('get-columns', 'GeneratorController@getColumns');
                    Route::post('relationship', 'GeneratorController@generateRelationship');
                    Route::get('diagram', 'GeneratorController@generateDiagram');
                });
                Route::apiResource('generators', 'GeneratorController');
                Route::apiResource('roles', 'RoleController');
                Route::apiResource('permissions', 'PermissionController');

                Route::apiResource('users', 'UserController');

                //{{ROUTE_ADMIN_NOT_DELETE_THIS_LINE}}
            });

            //{{ROUTE_USER_NOT_DELETE_THIS_LINE}}
        });
    });
});

Route::fallback('LarajsController@fallbackApi');
