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

//Home page controller
Route::get('news', 'HomeController@getNews')->name('getNews');
Route::get('getLatesNews', 'HomeController@getLatestNews')->name('getLatestNews');
Route::get('topFivePlayers', 'HomeController@getTopFivePlayers');
Route::post('topFivePlayersDivision', 'HomeController@topFivePlayersDivision');
Route::post('payment', 'PaymentController@payment');
Route::post('users', 'HomeController@getUsers');
Route::post('getCurrentRound', 'HomeController@getCurrentRound');

Route::get('getJersey/{name}', 'HomeController@getJersey');


//Player page Controller
Route::get('players/getPlayers', 'PlayerController@getPlayers');

//My Team page controller

Route::post('myTeam/getMyTeamPage', 'SquadController@getMyTeamPage')->middleware('jwt.auth');
Route::put('myTeam/updateSquad', 'SquadController@updateSquad')->middleware('jwt.auth');
Route::post('myTeam/postSquad', 'SquadController@postSquad')->middleware('jwt.auth');
Route::post('myTeam/buyPlayer', 'SquadController@buyPlayer');
Route::delete('myTeam/sellPlayer', 'SquadController@sellPlayer')->middleware('jwt.auth');
// duplicated routes(admin)
Route::post('myTeam/getClubs', 'AdminController@getClubs')->middleware('check.admin');
Route::post('myTeam/getClub', 'AdminController@getClub')->middleware('jwt.auth');;
Route::post('myTeam/getPlayer', 'SquadController@getPlayer');
Route::post('myTeam/getPlayers', 'SquadController@getPlayers');
Route::post('myTeam/getSquad', 'SquadController@getSquad');
Route::post('myTeam/checkTransfer', 'SquadController@checkTransfer');





// Admin routes

//club
Route::post('admin/getClubs', 'AdminController@getClubs');
Route::post('admin/getClub', 'AdminController@getClub');
Route::post('admin/postClub', 'AdminController@postClub');
Route::post('admin/removeClub', 'AdminController@removeClub');
Route::put('admin/updateClub', 'AdminController@updateClub');
//player
Route::post('admin/getPlayers', 'AdminController@getPlayers');
Route::post('admin/getPlayersByClub', 'AdminController@getPlayersByClub');
Route::post('admin/getPlayer', 'AdminController@getPlayer');
Route::post('admin/postPlayer', 'AdminController@postPlayer');
Route::post('admin/removePlayer', 'AdminController@removePlayer');
Route::put('admin/updatePlayer', 'AdminController@updatePlayer');
//league
Route::get('admin/getLeagues', 'AdminController@getLeagues');
Route::post('admin/getLeague', 'AdminController@getLeague');
Route::post('admin/postLeague', 'AdminController@postLeague');
Route::post('admin/removeLeague', 'AdminController@removeLeague');
Route::put('admin/updateLeague', 'AdminController@updateLeague');
//match
Route::post('admin/getMatches', 'AdminController@getMatches');
Route::post('admin/getMatchesByRounds', 'AdminController@getMatchesByRounds');
Route::post('admin/getMatch', 'AdminController@getMatch');
Route::post('admin/postMatch', 'AdminController@postMatch');
Route::post('admin/removeMatch', 'AdminController@removeMatch');
Route::post('admin/updateMatch', 'AdminController@updateMatch');
//round
Route::post('admin/getRounds', 'AdminController@getRounds');
Route::post('admin/getRound', 'AdminController@getRound');
Route::post('admin/postRound', 'AdminController@postRound');
Route::post('admin/removeRound', 'AdminController@removeRound');
Route::put('admin/updateRound', 'AdminController@updateRound');
//season
Route::get('admin/getSeasons', 'AdminController@getSeasons');
Route::post('admin/getSeason', 'AdminController@getSeason');
Route::post('admin/postSeason', 'AdminController@postSeason');
Route::post('admin/removeSeason', 'AdminController@removeSeason');
Route::put('admin/updateSeason', 'AdminController@updateSeason');
//user
Route::get('admin/getUsers', 'AdminController@getUsers');
Route::post('admin/getUser', 'AdminController@getUser');
Route::post('admin/postUser', 'AdminController@postUser');
Route::post('admin/removeUser', 'AdminController@removeUser');
Route::put('admin/updateUser', 'AdminController@updateUser');
//news
Route::get('admin/getArticles', 'AdminController@getArticles');
Route::post('admin/getArticle', 'AdminController@getArticle');
Route::post('admin/postArticle', 'AdminController@postArticle');
Route::post('admin/removeArticle', 'AdminController@removeArticle');
Route::put('admin/updateArticle', 'AdminController@updateArticle');
//post match statistic
Route::post('admin/getClubsByMatch', 'AdminController@getClubsByMatch');
Route::post('admin/getPlayersStats', 'AdminController@getPlayersStats');
Route::post('admin/postPlayerStats', 'AdminController@postPlayerStats');

