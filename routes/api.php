<?php

use Illuminate\Http\Request;

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

//In order to keep links simple and uniform, we use post routes for geting(instaid of passing id's to links)

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('user/register', 'APIRegisterController@register');
Route::post('user/login', 'APILoginController@login');

Route::middleware('jwt.auth')->get('/users', function(Request $request) {
    return auth()->user();
});
Route::get('news', 'HomeController@getNews')->name('getNews');
Route::get('getLatesNews', 'HomeController@getLatestNews')->name('getLatestNews');
Route::get('topFivePlayers', 'HomeController@getTopFivePlayers');
Route::post('topFivePlayersDivision', 'HomeController@topFivePlayersDivision');


// Admin routes
Route::get('getClubs', 'AdminController@getClubs');
Route::post('getClub', 'AdminController@getClub');
//Club create routes here

Route::get('getLeagues', 'AdminController@getLeagues');
Route::post('getLeague', 'AdminController@getLeague');
//League create routes here


Route::get('getMatches', 'AdminController@getMatches');
Route::post('getMatch', 'AdminController@getMatch');
//Match create routes here

Route::get('getRounds', 'AdminController@getRounds');
Route::post('getRound', 'AdminController@getRound');
//Match create routes here
