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
Route::get('news', 'HomeController@getAllNews');
Route::post('getLatestNews', 'HomeController@getLatestNews');
Route::get('topFivePlayers', 'HomeController@getTopFivePlayers');
Route::put('updateUserSettings', 'UserController@updateUserSettings')->middleware('jwt.auth');
Route::get('getUserSettings', 'UserController@getUserSettings')->middleware('jwt.auth');
Route::put('changePassword', 'UserController@changePassword')->middleware('jwt.auth');
Route::post('sendResetPassword', 'UserController@sendResetPassword');
Route::post('confirmPassword', 'UserController@confirmPassword');
Route::get('getNewPassword/{token}', 'UserController@getNewPassword');
//refactor ??
Route::get('topFivePlayersDivision1', 'HomeController@topFivePlayersDivision1');
Route::get('topFivePlayersDivision2', 'HomeController@topFivePlayersDivision2');
//
Route::post('payment', 'PaymentController@payment');
Route::post('users', 'HomeController@getUsers');
Route::post('getCurrentRound', 'HomeController@getCurrentRound');
Route::post('getUserSquad', 'HomeController@getUserSquad');
Route::get('getAllLeagues', 'HomeController@getAllLeagues');
Route::get('getMyLeagues', 'HomeController@getMyLeagues')->middleware('jwt.auth');
Route::post('getNews', 'HomeController@getNewsByLeague');
Route::post('getAllPoints', 'HomeController@getAllPoints')->middleware('jwt.auth');
Route::post('getUserRank', 'HomeController@getUserRank')->middleware('jwt.auth');
Route::get('getRankingTable/{l_id}', 'HomeController@getRankingTable');
Route::post('getUserStats', 'HomeController@getUserStats');
Route::post('getUsersStats', 'HomeController@getUsersStats');

Route::get('getJersey/{name}', 'HomeController@getJersey');
Route::post('saveImage', 'HomeController@saveImage');
Route::get('viewFile/{name}', 'HomeController@viewFile');
Route::get('getImage/{name}', 'HomeController@getImage');
Route::post('getDreamTeam', 'HomeController@getDreamTeam');
Route::get('getJerseyId/{$id}', 'HomeController@getJerseyId');
Route::get('getArticle/{slug}', 'HomeController@getArticle');
Route::post('sendTip', 'HomeController@sendTip')->middleware('jwt.auth');
Route::post('checkUser', 'HomeController@checkUser');
Route::post('getPlayerInfo', 'HomeController@getPlayerInfo');



//Player page routes
Route::get('players/getPlayers', 'PlayerController@getPlayers');

//My Team page routes

Route::post('myTeam/getMyTeamPage', 'SquadController@getMyTeamPage')->middleware('jwt.auth');
Route::put('myTeam/updateSquad', 'SquadController@updateSquad')->middleware('jwt.auth');
Route::post('myTeam/postSquad', 'SquadController@postSquad')->middleware('jwt.auth');
Route::post('myTeam/makeTransfer', 'SquadController@makeTransfer')->middleware('jwt.auth');
Route::delete('myTeam/sellPlayer', 'SquadController@sellPlayer')->middleware('jwt.auth');
// duplicated routes(admin)
Route::post('myTeam/getClubs', 'AdminController@getClubs');
Route::post('myTeam/getClub', 'AdminController@getClub');
Route::post('myTeam/getPlayer', 'SquadController@getPlayer');
Route::post('myTeam/getPlayers', 'SquadController@getPlayers');
Route::post('myTeam/getSquad', 'SquadController@getSquad');
Route::post('myTeam/getNextRound', 'SquadController@getNextRound');
Route::post('myTeam/checkTransfer', 'SquadController@checkTransfer')->middleware('jwt.auth');
Route::post('myTeam/hasSquad', 'SquadController@hasSquad')->middleware('jwt.auth');

//Private League routes
Route::post('privateLeague/createLeague', 'PrivateLeagueController@createLeague')->middleware('jwt.auth');
Route::post('privateLeague/getPrivateLeagues', 'PrivateLeagueController@getPrivateLeagues')->middleware('jwt.auth');
Route::post('privateLeague/leaveLeague', 'PrivateLeagueController@leaveLeague')->middleware('jwt.auth');
Route::post('privateLeague/deleteLeague', 'PrivateLeagueController@deleteLeague')->middleware('jwt.auth');
Route::post('privateLeague/joinLeague', 'PrivateLeagueController@joinLeague')->middleware('jwt.auth');
Route::post('privateLeague/createLeague', 'PrivateLeagueController@createLeague')->middleware('jwt.auth');
Route::post('privateLeague/sendInvite', 'PrivateLeagueController@sendInvite')->middleware('jwt.auth');
Route::post('privateLeague/banUser', 'PrivateLeagueController@banUser')->middleware('jwt.auth');
Route::post('privateLeague/showTable', 'PrivateLeagueController@showTable')->middleware('jwt.auth');
Route::post('privateLeague/getTable', 'PrivateLeagueController@getTable')->middleware('jwt.auth');
Route::get('privateLeague/joinLeague/{code}', 'PrivateLeagueController@joinLeagueLink')->middleware('jwt.auth');






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
Route::put('admin/setDeadline', 'AdminController@setDeadline');
//season
Route::get('admin/getSeasons', 'AdminController@getSeasons');
Route::post('admin/getSeason', 'AdminController@getSeason');
Route::post('admin/postSeason', 'AdminController@postSeason');
Route::post('admin/removeSeason', 'AdminController@removeSeason');
Route::put('admin/updateSeason', 'AdminController@updateSeason');
//user
Route::post('admin/getUsers', 'AdminController@getUsers');
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
Route::post('admin/playerTotalPoints', 'AdminController@playerTotalPoints');
// tips
Route::post('admin/getTips', 'AdminController@getTips');
Route::post('admin/getTip', 'AdminController@getTip');
Route::post('admin/removeTip', 'AdminController@removeTip');

Route::post('admin/evaluateUserPoints', 'AdminController@evaluateUserPoints');

