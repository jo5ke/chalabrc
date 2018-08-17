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




// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route::middleware('jwt.auth')->get('/users', function(Request $request) {
//     return auth()->user();
// });


//In order to keep links simple and uniform, we use post routes for geting(instaid of passing id's to links)

Route::post('user/register', 'APIRegisterController@register');
Route::post('user/login', 'APILoginController@login');

//Home page controller
Route::get('news', 'HomeController@getAllNews');
Route::post('getLatestNews', 'HomeController@getLatestNews');
Route::get('getLatestNewsByLeague', 'HomeController@getLatestNewsByLeague'); // post->get
Route::get('topFivePlayers', 'HomeController@getTopFivePlayers');
Route::get('getArticle/{slug}', 'HomeController@getArticle');

Route::get('getUserSettings', 'UserController@getUserSettings')->middleware('jwt.auth');
Route::put('updateUserSettings', 'UserController@updateUserSettings')->middleware('jwt.auth');
Route::put('changePassword', 'UserController@changePassword')->middleware('jwt.auth');
Route::post('sendResetPassword', 'UserController@sendResetPassword');
Route::post('confirmPassword', 'UserController@confirmPassword');
Route::get('getNewPassword/{token}', 'UserController@getNewPassword');
// Route::post('unlockFreeLeague', 'UserController@unlockFreeLeague')->middleware('jwt.auth');

Route::post('users', 'HomeController@getUsers');

Route::post('payment', 'PaymentController@payment');
Route::post('additionalPayment', 'PaymentController@additionalPayment')->middleware('jwt.auth');
Route::post('unlockFreeLeague', 'PaymentController@unlockFreeLeague')->middleware('jwt.auth');


Route::post('getUserPointsPerRound', 'HomeController@getUserPointsPerRound');
Route::post('getCurrentRound', 'HomeController@getCurrentRound');
Route::post('getUserSquad', 'HomeController@getUserSquad');

Route::get('getAllLeagues', 'HomeController@getAllLeagues');
Route::get('getMyLeagues', 'HomeController@getMyLeagues')->middleware('jwt.auth');

Route::post('getNews', 'HomeController@getNewsByLeague');
Route::get('getAllPoints', 'HomeController@getAllPoints')->middleware('jwt.auth');
Route::get('getUserRank', 'HomeController@getUserRank')->middleware('jwt.auth');
Route::get('getRankingTable/{l_id}', 'HomeController@getRankingTable');
Route::post('getUserStats', 'HomeController@getUserStats');
Route::get('getUsersStats', 'HomeController@getUsersStats');
Route::post('sendTip', 'HomeController@sendTip')->middleware('jwt.auth');
Route::get('getDreamTeam', 'HomeController@getDreamTeam');

Route::get('getJersey/{name}', 'HomeController@getJersey');
Route::get('getJerseyId/{$id}', 'HomeController@getJerseyId');
Route::get('getImage/{name}', 'HomeController@getImage');
// Route::post('saveImage', 'HomeController@saveImage');
// Route::get('viewFile/{name}', 'HomeController@viewFile');
Route::post('checkUser', 'HomeController@checkUser');
Route::get('getPlayerInfo', 'HomeController@getPlayerInfo');
Route::post('getMatchInfo', 'HomeController@getMatchInfo');

//My Team page routes


Route::middleware(['jwt.auth'])->group(function () {
    Route::get('myTeam/getMyTeamPage', 'SquadController@getMyTeamPage');
    Route::put('myTeam/updateSquad', 'SquadController@updateSquad');
    Route::post('myTeam/postSquad', 'SquadController@postSquad');
    Route::post('myTeam/makeTransfer', 'SquadController@makeTransfer');
    // Route::delete('myTeam/sellPlayer', 'SquadController@sellPlayer');
    // Route::post('myTeam/transferRevert', 'SquadController@transferRevert');
    Route::post('myTeam/checkTransfer', 'SquadController@checkTransfer');
    Route::post('myTeam/hasSquad', 'SquadController@hasSquad');
});

// admin getters 
Route::get('myTeam/getClubs', 'AdminController@getClubs');
Route::post('myTeam/getClub', 'AdminController@getClub');

Route::get('myTeam/getPlayers', 'SquadController@getPlayers');
Route::get('myTeam/getNextRound', 'SquadController@getNextRound');



//Private League routes

Route::middleware(['jwt.auth'])->group(function () {
    Route::post('privateLeague/createLeague', 'PrivateLeagueController@createLeague');
    Route::get('privateLeague/getPrivateLeagues', 'PrivateLeagueController@getPrivateLeagues');
    Route::post('privateLeague/leaveLeague', 'PrivateLeagueController@leaveLeague');
    Route::delete('privateLeague/deleteLeague', 'PrivateLeagueController@deleteLeague');
    Route::post('privateLeague/joinLeague', 'PrivateLeagueController@joinLeague');
    Route::post('privateLeague/sendInvite', 'PrivateLeagueController@sendInvite');
    Route::post('privateLeague/banUser', 'PrivateLeagueController@banUser');
    Route::post('privateLeague/showTable', 'PrivateLeagueController@showTable');
    Route::post('privateLeague/getTable', 'PrivateLeagueController@getTable');
});
Route::get('privateLeague/joinLeague/{email}/{code}', 'PrivateLeagueController@joinLeagueLink');

// Admin routes

Route::middleware(['check.admin'])->group(function () {

    //club
    Route::get('admin/getClubs', 'AdminController@getClubs');
    Route::get('admin/getClub', 'AdminController@getClub');
    Route::post('admin/postClub', 'AdminController@postClub');
    Route::delete('admin/removeClub', 'AdminController@removeClub');
    Route::put('admin/updateClub', 'AdminController@updateClub');
    //player
    Route::get('admin/getPlayers', 'AdminController@getPlayers');
    Route::get('admin/getPlayersByClub', 'AdminController@getPlayersByClub');
    Route::get('admin/getPlayer', 'AdminController@getPlayer');
    Route::post('admin/postPlayer', 'AdminController@postPlayer');
    Route::delete('admin/removePlayer', 'AdminController@removePlayer');
    Route::put('admin/updatePlayer', 'AdminController@updatePlayer');
    //league
    Route::get('admin/getLeagues', 'AdminController@getLeagues');
    Route::get('admin/getLeague', 'AdminController@getLeague');
    Route::post('admin/postLeague', 'AdminController@postLeague');
    Route::delete('admin/removeLeague', 'AdminController@removeLeague');
    Route::put('admin/updateLeague', 'AdminController@updateLeague');
    //match
    Route::get('admin/getMatches', 'AdminController@getMatches');
    Route::get('admin/getMatchesByRounds', 'AdminController@getMatchesByRounds');
    Route::get('admin/getMatch', 'AdminController@getMatch');
    Route::post('admin/postMatch', 'AdminController@postMatch');
    Route::delete('admin/removeMatch', 'AdminController@removeMatch');
    Route::put('admin/updateMatch', 'AdminController@updateMatch');
    //round
    Route::get('admin/getRounds', 'AdminController@getRounds');
    Route::get('admin/getRound', 'AdminController@getRound');
    // Route::post('admin/postRound', 'AdminController@postRound');
    // Route::delete('admin/removeRound', 'AdminController@removeRound');
    // Route::put('admin/updateRound', 'AdminController@updateRound');
    Route::put('admin/setDeadline', 'AdminController@setDeadline');
    //season
    // Route::get('admin/getSeasons', 'AdminController@getSeasons');
    // Route::get('admin/getSeason', 'AdminController@getSeason');
    // Route::post('admin/postSeason', 'AdminController@postSeason');
    // Route::delete('admin/removeSeason', 'AdminController@removeSeason');
    // Route::put('admin/updateSeason', 'AdminController@updateSeason');
    //user
    Route::get('admin/getUsers', 'AdminController@getUsers');
    Route::get('admin/getUser', 'AdminController@getUser');
    Route::post('admin/postUser', 'AdminController@postUser');
    Route::delete('admin/removeUser', 'AdminController@removeUser');
    Route::put('admin/updateUser', 'AdminController@updateUser');
    //squads 
    Route::get('admin/getSquads', 'AdminController@getSquads');
    Route::delete('admin/resetSquad', 'AdminController@resetSquad');
    //admin
    Route::get('admin/getAdmins', 'AdminController@getAdmins');
    Route::get('admin/getAdmin', 'AdminController@getAdmin');
    Route::post('admin/postAdmin', 'AdminController@postAdmin');
    Route::delete('admin/removeAdmin', 'AdminController@removeAdmin');
    Route::put('admin/updateAdmin', 'AdminController@updateAdmin');
    Route::post('admin/makeAdmin', 'AdminController@makeAdmin');
    Route::post('admin/unMakeAdmin', 'AdminController@unMakeAdmin');
    //news
    Route::get('admin/getArticles', 'AdminController@getArticles');
    Route::get('admin/getArticle', 'AdminController@getArticle');
    Route::post('admin/postArticle', 'AdminController@postArticle');
    Route::delete('admin/removeArticle', 'AdminController@removeArticle');
    Route::put('admin/updateArticle', 'AdminController@updateArticle');
    //post match statistic
    Route::get('admin/getClubsByMatch', 'AdminController@getClubsByMatch');
    Route::get('admin/getPlayersStats', 'AdminController@getPlayersStats');
    Route::post('admin/postPlayerStats', 'AdminController@postPlayerStats');
    Route::post('admin/playerTotalPoints', 'AdminController@playerTotalPoints');
    // tips
    Route::get('admin/getTips', 'AdminController@getTips');
    Route::get('admin/getTip', 'AdminController@getTip');
    Route::delete('admin/removeTip', 'AdminController@removeTip');
    
    Route::post('admin/evaluateUserPoints', 'AdminController@evaluateUserPoints');
    Route::post('admin/prevRound', 'AdminController@prevRound');
    Route::post('admin/nextRound', 'AdminController@nextRound');

    Route::post('admin/newsletter', 'AdminController@sendNewsletter');

});