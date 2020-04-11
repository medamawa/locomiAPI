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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register', 'JWTAuthController@register')->name('api.jwt.register');
Route::post('/login', 'JWTAuthController@login')->name('api.jwt.login');
Route::get('/unauthorized', function() {
    return response()->json([
        'status' => 'error',
        'message' => 'Unauthorized',
    ], 401);
})->name('api.jwt.unauthorized');

// ログイン状態
Route::group(['middleware' => 'auth:api'], function () {
    Route::resource('/users', 'UsersController', ['only' => ['index', 'show', 'edit', 'update']]);

    Route::get('/user', 'JWTAuthController@user')->name('api.jwt.user');
    Route::post('/logout', 'JWTAuthController@logout');
    Route::post('/refresh', 'JWTAuthController@refresh');

    Route::post('/users/{user}/follow', 'UsersController@follow')->name('follow');
    Route::delete('/users/{user}/unfollow', 'UsersController@unfollow')->name('unfollow');
});
