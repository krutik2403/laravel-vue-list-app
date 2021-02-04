<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(['namespace' => 'App\Http\Controllers'], function () {
    Route::group(['prefix' => 'users'], function () {
        Route::post('/', 'UsersController@create');
        Route::get('/', 'UsersController@index');
        Route::get('/{user}', 'UsersController@show');
        Route::delete('/{user}', 'UsersController@delete');
        Route::post('/{user}', 'UsersController@update');
    });
});
