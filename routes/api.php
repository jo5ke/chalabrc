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
Route::get('players/getPlayers', 'PlayerController@getPlayers');

Route::get('myTeam/getPlayers', 'SquadController@getAllPlayers');



// Admin routes

//club
Route::get('admin/getClubs', 'AdminController@getClubs');
Route::post('admin/getClub', 'AdminController@getClub');
Route::post('admin/postClub', 'AdminController@postClub');
Route::post('admin/removeClub', 'AdminController@removeClub');
//league
Route::get('admin/getLeagues', 'AdminController@getLeagues');
Route::post('admin/getLeague', 'AdminController@getLeague');
Route::post('admin/postLeague', 'AdminController@postLeague');
Route::post('admin/removeLeague', 'AdminController@removeLeague');
//match
Route::get('admin/getMatches', 'AdminController@getMatches');
Route::post('admin/getMatch', 'AdminController@getMatch');
Route::post('admin/postMatch', 'AdminController@postMatch');
Route::post('admin/removeMatch', 'AdminController@removeMatch');
//round
Route::get('admin/getRounds', 'AdminController@getRounds');
Route::post('admin/getRound', 'AdminController@getRound');
Route::post('admin/postRound', 'AdminController@postRound');
Route::post('admin/removeRound', 'AdminController@removeRound');
//season
Route::get('admin/getSeasons', 'AdminController@getSeasons');
Route::post('admin/getSeason', 'AdminController@getSeason');
Route::post('admin/postSeason', 'AdminController@postSeason');
Route::post('admin/removeSeason', 'AdminController@removeSeason');
//user
Route::get('admin/getUsers', 'AdminController@getUsers');
Route::post('admin/getUser', 'AdminController@getUser');
Route::post('admin/postUser', 'AdminController@postUser');
Route::post('admin/removeUser', 'AdminController@removeUser');

