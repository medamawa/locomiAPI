<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::get('/users', 'UsersController@index')->name('api.users.index');
Route::get('/users/{id}', 'UsersController@show')->name('api.users.show');

Route::get('/comics', 'ComicsController@index')->name('api.comics.index');
Route::get('/comics/{id}', 'ComicsController@show')->name('api.comics.show');

// ログイン状態
Route::group(['middleware' => 'auth:api'], function () {
    Route::post('/users', 'UsersController@update')->name('api.users.update');

    Route::get('/user', 'JWTAuthController@user')->name('api.jwt.user');
    Route::post('/logout', 'JWTAuthController@logout');
    Route::post('/refresh', 'JWTAuthController@refresh');

    Route::post('/follow', 'UsersController@follow')->name('api.follow');
    Route::get('/follows', 'UsersController@follows')->name('api.follows.index');
    Route::get('/followers', 'UsersController@followers')->name('api.followers.index');

    Route::post('/comic', 'ComicsController@store')->name('api.comic.store');
    Route::delete('/comic/{id}', 'ComicsController@destroy')->name('api.comic.delete');

    Route::post('/comment', 'CommentsController@store')->name('api.comment.store');
    Route::delete('/comment/{id}', 'CommentsController@destroy')->name('api.comment.delete');

    Route::post('/favorite', 'FavoritesController@favorite')->name('api.favorite');
});
