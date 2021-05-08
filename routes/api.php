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
    Route::group(['middleware' => 'auth:api'], function () {
        // FILE
        Route::middleware('optimizeImages')->group(function () {
            // all images will be optimized automatically
            Route::post('upload-file/store', 'FileController@store');
            Route::post('upload-file/store-avatar', 'FileController@storeAvatar');
            Route::get('upload-file/remove', 'FileController@remove');
        });

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
        });
    });
});

Route::fallback(function() {
    return response()->json(
        [
            'message' => trans('error.404'),
        ],
        404,
    );
});
