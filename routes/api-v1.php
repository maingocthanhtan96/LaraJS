<?php

// Send reset password mail
Route::post('/forgot-password', 'AuthController@forgotPassword');
// Handle reset password form process
Route::post('/reset-password', 'AuthController@callResetPassword');
// START - Auth
Route::post('/logging', 'AuthController@logging');
Route::post('/fe-login', 'AuthController@feLogin');
Route::post('/refresh-token', 'AuthController@refreshToken');
Route::post('/login', 'AuthController@login')->name('login');
// END - Auth
Route::group(['middleware' => 'auth:api'], function () {
    Route::get('/fe-logout', 'AuthController@feLogout');

    Route::get('/user-info', 'UserController@userInfo');
    Route::get('/logout', 'AuthController@logout')->name('logout');
    Route::group(['middleware' => 'verify_request'], function () {

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
    Route::group(['middleware' => 'role:' . \ACL::ROLE_ADMIN], function () {
        Route::apiResource('users', 'UserController');
        //{{ROUTE_ADMIN_NOT_DELETE_THIS_LINE}}
    });

    //{{ROUTE_USER_NOT_DELETE_THIS_LINE}}
});

Route::fallback(function() {
    return response()->json(
        [
            'message' => trans('error.404'),
        ],
        404,
    );
});
