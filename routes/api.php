<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::prefix('test_run')->group(function() {
    Route::post('/register', 'RegisterController@register');
    Route::post('/login', 'LoginController@login');
    Route::middleware(['jwt_refresh'])->group(function() {
        Route::post('/refresh-token', 'RefreshTokenController@refreshToken');
    });
});

Route::post('/register', 'JWTAuthController@register')->name('api.jwt.register');
Route::post('/login', 'JWTAuthController@login')->name('api.jwt.login');
Route::get('/unauthorized', function() {
    return response()->json([
        'status' => 'error',
        'auth_message' => 'Unauthorized',
    ], 401);
})->name('api.jwt.unauthorized');

Route::get('/users', 'UsersController@index')->name('api.users.index');
Route::get('/users/{id}', 'UsersController@show')->name('api.users.show');

Route::get('/comics/all', 'ComicsController@index')->name('api.comics.all.index');
Route::get('/comics/all/{id}', 'ComicsController@show')->name('api.comics.all.show');
Route::get('/comics/user/{user_id}', 'ComicsController@index_user')->name('api.comics.user.index');


// ログイン状態
Route::group(['middleware' => 'auth:api'], function () {
    // updateは保留中
    Route::post('/users', 'UsersController@update')->name('api.users.update');

    // userは消去保留中
    Route::get('/user', 'JWTAuthController@user')->name('api.jwt.user');
    Route::get('/logout', 'JWTAuthController@logout')->name('api.jwt.logout');
    Route::get('/refresh', 'JWTAuthController@refresh')->name('api.jwt.refresh');

    Route::post('/follow', 'UsersController@follow')->name('api.follow');
    Route::get('/follows', 'UsersController@follows')->name('api.follows.index');
    Route::get('/followers', 'UsersController@followers')->name('api.followers.index');

    Route::post('/post', 'ComicsController@store')->name('api.comic.store');
    Route::delete('/comic/{id}', 'ComicsController@destroy')->name('api.comic.delete');

    Route::post('/comment', 'CommentsController@store')->name('api.comment.store');
    Route::delete('/comment/{id}', 'CommentsController@destroy')->name('api.comment.delete');

    Route::post('/favorite', 'FavoritesController@favorite')->name('api.favorite');
});
