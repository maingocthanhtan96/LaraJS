<?php
Route::group(['prefix' => 'laravel-filemanager', 'middleware' => 'optimizeImages'], function () {
    \UniSharp\LaravelFilemanager\Lfm::routes();
});
Route::get('/language/{language}', 'LaraJSController@setLanguage');
Route::post('/logging', 'LaraJSController@logging');
// Send reset password mail
Route::post('/forgot-password', 'AuthController@forgotPassword');
// Handle reset password form process
Route::post('/reset-password', 'AuthController@resetPassword');
// START - Auth
Route::post('/fe-login', 'AuthController@feLogin');
Route::post('/refresh-token', 'AuthController@refreshToken');
Route::post('/login', 'AuthController@login');
// END - Auth
Route::group(['middleware' => 'auth:api'], function () {
    Route::get('/fe-logout', 'AuthController@feLogout');
    Route::get('/user-info', 'UserController@userInfo');
    Route::get('/logout', 'AuthController@logout');
    Route::group(['middleware' => 'verify_request'], function () {});
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
            Route::get('get-all-models', 'GeneratorController@getAllModels');
            Route::get('get-columns', 'GeneratorController@getColumns');
            Route::post('relationship', 'GeneratorController@generateRelationship');
            Route::get('diagram', 'GeneratorController@generateDiagram');
        });
        Route::apiResource('generators', 'GeneratorController');
        Route::apiResource('users', 'UserController');
        //{{ROUTE_ADMIN_NOT_DELETE_THIS_LINE}}
    });

    //{{ROUTE_USER_NOT_DELETE_THIS_LINE}}
});

Route::fallback(function () {
    return response()->json(
        [
            'message' => trans('error.404'),
        ],
        404
    );
});
