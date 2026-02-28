<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/login', 'Auth\LoginController@showLogin')->name('login.show');
Route::post('/login', 'Auth\LoginController@login')->name('login.perform');
Route::get('/logout', 'Auth\LoginController@logout')->name('logout');

Route::group(['middleware' => 'auth.check'], function () {
    Route::get('/', 'MovieController@indexPage')->name('movies.index');
    Route::get('/movies', 'MovieController@indexPage')->name('movies.index');
    Route::get('/movies/{id}', 'MovieController@detailPage')->name('movies.detail');
    Route::get('/favorites', 'MovieController@favoritesPage')->name('movies.favorites');

    Route::get('/movies-api', 'MovieController@index')->name('movies.api');
    Route::get('/movies-api/{id}', 'MovieController@detail')->name('movies.api.detail');
    Route::post('/favorites', 'MovieController@addFavorite')->name('favorites.add');
    Route::delete('/favorites/{id}', 'MovieController@removeFavorite')->name('favorites.remove');
});

Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'id'])) {
        session(['locale' => $locale]);
        app()->setLocale($locale);
    }
    return redirect()->back();
});
