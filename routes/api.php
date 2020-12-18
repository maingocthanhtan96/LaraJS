<?php

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

Route::group(['prefix' => 'laravel-filemanager'], function () {
    \UniSharp\LaravelFilemanager\Lfm::routes();
});

Route::group(['namespace' => 'Api'], function () {
    Route::get('/language/{language}', 'LangController@setLanguage');
    // Send reset password mail
    Route::post('/forgot-password', 'AuthController@forgotPassword');
    // Handle reset password form process
    Route::post('/reset-password', 'AuthController@callResetPassword');
    // Login
    Route::post('/login', 'AuthController@login')->name('login');
    // Logs
    Route::group(['prefix' => 'logs'], function () {
        Route::post('error', 'LogController@error');
        Route::post('warn', 'LogController@warn');
    });

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

        // permission manage permission
        Route::group(
            [
                'middleware' => 'permission:' . \ACL::PERMISSION_PERMISSION_MANAGE,
            ],
            function () {
                Route::apiResource('roles', 'RoleController');
                Route::apiResource('permissions', 'PermissionController');
            }
        );

        // role Admin (Super admin)
        Route::group(['middleware' => 'role:' . \ACL::ROLE_ADMIN], function () {
            Route::group(['prefix' => 'generators'], function () {
                Route::get('check-model', 'GeneratorController@checkModel');
                Route::get('check-column', 'GeneratorController@checkColumn');
                Route::get('get-models', 'GeneratorController@getModels');
                Route::get('get-columns', 'GeneratorController@getColumns');
                Route::post('relationship', 'GeneratorController@generateRelationship');
                Route::get('diagram', 'GeneratorController@generateDiagram');
            });
            Route::apiResource('generators', 'GeneratorController');

            Route::apiResource('users', 'UserController');
        });
    });
});

Route::group(['prefix' => 'v1', 'namespace' => 'Api\v1'], function () {
    Route::group(['middleware' => 'auth:api'], function () {
        Route::group(['middleware' => 'role:' . \ACL::ROLE_ADMIN], function () {
            //{{ROUTE_ADMIN_NOT_DELETE_THIS_LINE}}
        });

        //{{ROUTE_USER_NOT_DELETE_THIS_LINE}}
    });
});

Route::fallback('LarajsController@fallbackApi');
