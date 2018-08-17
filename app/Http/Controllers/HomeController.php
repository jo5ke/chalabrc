<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Article as Article;
use App\User as User;
use App\League as League;
use App\Tip as Tip;
use App\Club as Club;
use App\Match as Match;
use App\Round as Round;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\File;
use App\Squad as Squad;
use App\Player as Player;
use Image;
use JWTAuth;
use Mail as Mail;
use App\Mail\RegistrationMail;
use App\Mail\SendTip;


/**
 * @resource Home
 *
 * Home,Points,Ranking,Stats,News pages routes
 */
class HomeController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public $term = "";


    /**
     * Get all leagues
     *
     * Fetch all existing leagues
     * 
     * 
     */
    public function getAllLeagues()
    {
        $results = League::all();
        if ($results === null) {
            $response = 'There was a problem fetching leagues.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

    /**
     * Get user's leagues
     *
     * Fetch leagues logged in user is playing (jwt auth token required)
     * 
     */
    public function getMyLeagues()
    {
        $user = JWTAuth::authenticate();
        $i=0;
       // return $user->leagues()->get();
        foreach( $user->leagues()->get() as $league){
            $results[$i] = $league;
            $i++;
        }
     //   $results = League::where();
        if ($results === null) {
            $response = 'There was a problem fetching leagues.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

    /**
     * Get all news
     *
     * Fetch all public published news (public=1,published=1)
     * 
     */
    public function getAllNews()
    {
        $results = Article::where('public',1)->where('published',1)->get();
        if ($results === null) {
            $response = 'There was a problem fetching news.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

    /**
     * Get news per league
     *
     * Fetch all public published news per league (public=1,published=1) // params: l_id (league id)
     * 
     */
    public function getNewsByLeague(Request $request)
    {
        $results = Article::where('league_id', $request->l_id)->orWhere('public',1)->where('published',1)->orderBy('created_at','desc')->paginate(3);
        if ($results === null) {
            $response = 'There was a problem fetching news.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

    /**
     * Get latests news per league
     *
     * Fetch the last published article in certain league (published=1) // params: l_id (league id)
     * 
     */
    public function getLatestNewsByLeague(Request $request)
    {
        $results = Article::where('league_id',$request->l_id)->where('published',1)->orderBy('created_at', 'desc')->first();
 
        if ($results === null) {
            $response = 'There was a problem fetching news.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

    /**
     * Get latests news
     *
     * Fetch the last published article for homepage (published=1,public=1)
     * 
     */
    public function getLatestNews(Request $request)
    {
        $results = Article::where('public',1)->where('published',1)->orderBy('created_at', 'desc')->first();
 
        if ($results === null) {
            $response = 'There was a problem fetching news.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

    /**
     * Get top five users
     *
     * Fetch top 5 users by points for each league
     * 
     */
    public function getTopFivePlayers()
    {
        $leagues = League::all();
        $i = 0;
        foreach($leagues as $league){
            $results[$i] = DB::table('users')
                ->join('user_league','users.id','=','user_league.user_id')
                ->select('users.first_name','users.last_name','users.uuid','users.username',
                        'user_league.points','user_league.squad_id')
                ->where('user_league.league_id','=',$league->id)                        
                ->orderBy('user_league.points', 'desc')
                ->take(5)
                ->get();
            $i++;
        }

        if ($results === null) {
            $response = 'There was a problem fetching players.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

    // "id" = id lige (1 i 2)
    
    /**
     * Get top five users per league
     *
     * Fetch top 5 users by points per league // params: l_id (league id)
     * 
     */
    public function topFivePlayersDivision(Request $request)
    {
        $leagues = League::all()->get();
        $results = DB::table('users')
                    ->join('user_league','users.id','=','user_league.user_id')
                    ->select('users.first_name','users.last_name','users.uuid','users.username',
                            'user_league.points','user_league.squad_id')
                    ->where('user_league.league_id','=',$request->l_id)
                    ->orderBy('user_league.points', 'desc')
                    ->take(5)
                    ->get();

     //   $results = User::with('leagues')->where('id', $request->id)->take(5)->get();
        if ($results === null) {
            $response = 'There was a problem fetching players.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

    /**
     * Get jersey image
     *
     * Fetch image for club's jersey from local storage(clubs) // params: name (club name)
     * 
     */
    public function getJersey(Request $request)
    {
        // $file = Storage::disk('public')->get($request->name);
        // return new Response($file, 200);
        if(Storage::disk('clubs')->exists($request->name)){
            $response = Response::make(Storage::disk('clubs')->get($request->name));
        }else{
            $response = Response::make(Storage::disk('clubs')->get('dzoniDefault.png'));
        }
        $response->header('Content-Type', 'image/png');
        return $response;
    }

    // with i argument???

    /**
     * Get users on Ranking page 
     *
     * Fetch all users in league, or search them by name  // params: l_id (league id) optional: per_page(users to be displayed per page), term (string to be searched by last or first name)
     * 
     */
    public function getUsers(Request $request)
    {
        $league = League::where('id',$request->l_id)->first();
        $current_round = $league->current_round;
        if($current_round > 1){
            $prev_round = $current_round - 1;        
        }else{
            $prev_round = $current_round;
        }
        // if($league->current_round>1){
        //     $prev_round = $league->current_round-1;
        // }else{
        //     $prev_round = $league->current_round;
        // }
        $this->term = $request->term ? $request->term : "";
        $per_page = $request->per_page ? $request->per_page : null;

        $l_id = intval($request->l_id);
        if($this->term==""){
            $results = DB::table('users')
                    ->join('user_league','users.id','=','user_league.user_id')
                    // ->join('squads','users.id','=','squads.user_id')
                    ->join('squad_round','user_league.squad_id','=','squad_round.squad_id')
                    ->select('users.first_name','users.last_name','users.uuid','users.created_at','users.username',
                            'user_league.money','user_league.points','user_league.squad_id','squad_round.points as prev_round')
                    ->where('squad_round.round_no','=',$current_round)
                    ->where('user_league.league_id','=',$l_id)
                    ->orderBy('user_league.points','desc')
                    ->orderBy('users.username','asc');
                    // ->take(10)
                    // ->get();

        }else{
            $this->term = $this->term . "%";
            $results = DB::table('users')
                    ->join('user_league','users.id','=','user_league.user_id')
                    ->join('squads','users.id','=','squads.user_id')
                    ->join('squad_round','squads.id','=','squad_round.squad_id')
                    ->select('users.first_name','users.last_name','users.uuid','users.created_at','users.username',
                            'user_league.money','user_league.points','user_league.squad_id','squad_round.points as prev_round')
                    // ->where([
                    //             ['user_league.league_id','=',$request->l_id],
                    //             ['users.first_name','LIKE',$term]
                    //          ])
                    ->where('user_league.league_id','=',$request->l_id)
                    ->where(function ($query) {
                                $query->where('users.first_name','LIKE',$this->term)
                                ->orWhere('users.last_name','LIKE',$this->term);
                    })
                    ->where('squad_round.round_no','=',$current_round)
                        
                    ->orderBy('user_league.points','desc')
                    ->orderBy('users.username','asc');
                    // ->take(10)
                    // ->get();
        }
        if($per_page===null){
            $results = $results->get();
            $i=0;
            foreach($results as $result)
            {  
                $points[$i] = $result->points;
                $i++;
            }

            $i=0;
            foreach($results as $result)
            {
                $point = $result->points;
                $key[$i] = array_search($point,$points);
                $key[$i]++;
                $result->position = $key[$i]; 
            }

            $results = $results->take(10);  
        }else{
            $results = $results->paginate($per_page);
        }

        if ($results === null) {
            $response = 'There was a problem fetching players.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

    /**
     * Get users points per round
     *
     * Getter for all users with their points per round // params: l_id (league id) optional: per_page(users to be displayed per page), term (string to be searched by last or first name)
     * 
     */
    public function getUserPointsPerRound(Request $request)
    {
        $league = League::where('id',$request->l_id)->first();
        $this->term = $request->term ? $request->term : "";
        $per_page = $request->per_page ? $request->per_page : null;

        $gw = intval($request->gw);
        if($gw == 0){
            $gw = $league->current_round;
        }
        if($this->term==""){
            $results = DB::table('users')
                    ->join('user_league','users.id','=','user_league.user_id')
                    ->join('squads','users.id','=','squads.user_id')
                    ->join('squad_round','squads.id','=','squad_round.squad_id')
                    ->select('users.first_name','users.last_name','users.uuid','users.created_at','users.username',
                            'user_league.money','user_league.points','user_league.squad_id','squad_round.points as prev_round')
                    ->where('squad_round.round_no','=',$gw)
                    ->where('user_league.league_id','=',$request->l_id)
                    ->orderBy('prev_round','desc')
                    ->orderBy('users.username','asc');
                    // ->take(10)
                    // ->get();
                     
        }else{
            $this->term = $this->term . "%";
            $results = DB::table('users')
                    ->join('user_league','users.id','=','user_league.user_id')
                    ->join('squads','users.id','=','squads.user_id')
                    ->join('squad_round','squads.id','=','squad_round.squad_id')
                    ->select('users.first_name','users.last_name','users.uuid','users.created_at','users.username',
                            'user_league.money','user_league.points','user_league.squad_id','squad_round.points as prev_round')
                    // ->where([
                    //             ['user_league.league_id','=',$request->l_id],
                    //             ['users.first_name','LIKE',$term]
                    //          ])
                    ->where('user_league.league_id','=',$request->l_id)
                    ->where(function ($query) {
                                $query->where('users.first_name','LIKE',$this->term)
                                ->orWhere('users.last_name','LIKE',$this->term);
                    })
                    ->where('squad_round.round_no','=',$gw)
                        
                    ->orderBy('prev_round','desc')
                    ->orderBy('users.username','asc');
                    // ->take(10)
                    // ->get();

        }
        if($per_page===null){
            $results = $results->get();
            $i=0;
            foreach($results as $result)
            {  
                $points[$i] = $result->points;
                $i++;
            }

            $i=0;
            foreach($results as $result)
            {
                $point = $result->points;
                $key[$i] = array_search($point,$points);
                $key[$i]++;
                $result->position = $key[$i]; 
            }

            $results = $results->take(10);            
        }else{
            $results = $results->paginate($per_page);
        }

        if ($results === null) {
            $response = 'There was a problem fetching user points.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }


    /**
     * Get current round
     *
     * Getter for number of the current round in league // params: l_id (league id) 
     * 
     */
    public function getCurrentRound(Request $request)
    {
        $league = League::where('id', $request->l_id)->first();
        $results = $league->current_round;

        if ($results === null) {
            $response = 'There was a problem fetching players.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

    // helper function,unused

    public function saveImage(Request $request)
    { 
    //    try{
    //        $this->validate($request, [
    //            'file'  =>  'required|mimes:jpeg,png,pdf'
    //        ]);
    //    } catch (ValidationException $e) {
    //        return $this->json($e->getResponse()->original, 422);
    //    }  
    //    $file = $request->file('file');
    //    $name = sha1(time()."-".$file->getClientOriginalName());
    //    Storage::put($name, File::get($file));

    //    return $this->json($name);
    //    return response()->json($name);
        $png_url = "product-".time().".png";
        $path = public_path().'img/designs/' . $png_url;

        Image::make(file_get_contents($request->image))->save($path);     

        return $this->json($response);
    }

    // helper function,unused

    public function viewFile($name)
    {
        return response()->make(Storage::get($name), 200, [
            'Content-Type' => Storage::mimeType($name),
            'Content-Disposition' => 'inline; '.$name,
        ]);
    }
    
    //l_id,uuid

    /**
     * Get user squad 
     *
     * Getter for certain user squad with players, points, ranking stats // params: l_id (league id) , uuid (user's uuid)
     * 
     */
    public function getUserSquad(Request $request)
    {
        $user = User::where('uuid',$request->uuid)->first();
        $user2 = $user;

        $team = Squad::where('user_id',$user->id)->where('league_id',$request->l_id)->first();
        $starting = json_decode($team->selected_team);
        $subs = json_decode($team->substitutions);

        for($i=0;$i<count($starting);$i++){
            $starting[$i]=Player::where('id',$starting[$i])->with('club')->first();
        }

        for($i=0;$i<count($subs);$i++){
            $subs[$i]=Player::where('id',$subs[$i])->with('club')->first();
        }


        $user = User::where('uuid',$request->uuid)->first();
        $meta = $user->leagues->where('id',$request->l_id)->first();
        $meta = $meta->pivot;

        // user rank finding

            $results = DB::table('users')
                ->join('user_league','users.id','=','user_league.user_id')
                ->select('users.first_name','users.last_name','users.uuid','users.username',
                        'user_league.points','user_league.squad_id')
                ->where('user_league.league_id','=',$request->l_id)
                ->orderBy('user_league.points', 'desc')
                ->orderBy('users.username','asc')
                ->get();

            //this gets first position of points
            $i=0;
            foreach($results as $result)
            {
                $points[$i] = $result->points;
                $i++;
            }
            $key = array_search($meta->points,$points);
            $key++;

            $current_round = League::where('id',$request->l_id)->first()->current_round;
            $prev_round = $current_round-1;

            $previous = DB::table('squad_round')
                ->select('points')
                ->where([
                    ['round_no','=',$prev_round],
                    ['squad_id','=',$team->id]
                ])
                ->get();

            $current = DB::table('squad_round')
                ->select('points')
                ->where([
                    ['round_no','=',$current_round],
                    ['squad_id','=',$team->id]
                ])
                ->get();

        //

                            
        $results = [
            "user"      =>  $user,
            "squad"     =>  $team,
            "points"    =>  $meta->points,
            "players" => [
                "starting" => $starting,
                "subs" => $subs
            ],
            "position"  => $key,
            "previous"  => $previous,
            "current"   => $current

        ];

        if ($results === null) {
            $response = 'There was a problem fetching players.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

    
    /**
     * Get news image
     *
     * Getter for image from storage(news) // params: name
     * 
     */
    public function getImage($name)
    {
        $results = Response::make(Storage::disk('news')->get($name));
        $results->header('Content-Type', 'image/png');
        return $results;

        // if ($results === null) {
        //     $response = 'There was a problem fetching players.';
        //     return $this->json($response, 404);
        // }
        // return $this->json($results);
    }

    /**
     * Get jersey image by id
     *
     * Getter for image by club's id // params: id (club's id)
     * 
     */    
    public function getJerseyId($id)
    {
        $club = Club::where('id',$id)->first();
        $str = $club->name . ".png";
        $results = Response::make(Storage::disk('news')->get($str));
        $results->header('Content-Type', 'image/png');
        return $results;

        // if ($results === null) {
        //     $response = 'There was a problem fetching players.';
        //     return $this->json($response, 404);
        // }
        // return $this->json($results);
    }

    /**
     * Get dream team on homepage
     *
     * Getter for top 11 players in total and from last round (1 gk, 4 defs, 4 mid, 2 atk) // params: l_id (league id)
     * 
     */ 
    public function getDreamTeam(Request $request)
    {
        $current_round = $league = League::where('id',$request->l_id)->first()->current_round;
        if($current_round > 1){
            $current_round--;
        }
        $round = Round::where('league_id',$request->l_id)->where('round_no',$current_round)->first();
      
        $gk = DB::table('players')
                ->join('round_player','players.id','=','round_player.player_id')
                ->join('clubs','players.club_id','=','clubs.id')
                ->select('players.first_name','players.last_name','players.number','players.price','players.position','players.club_id','players.id',
                        (DB::raw('SUM(round_player.total) as total')),'clubs.name')
                ->where([
                            ['round_player.round_id','=',$round->id],
                            ['players.position','=','GK'],
                            ['clubs.league_id','=',$request->l_id],                            
                        ])
                ->groupBy('players.id','players.first_name','players.last_name','players.number','players.price','players.position','players.club_id','clubs.name')                        
                ->orderBy('total','desc')
                ->take(1)
                ->get();

        $gk_t = DB::table('players')
                ->join('round_player','players.id','=','round_player.player_id')
                ->join('clubs','players.club_id','=','clubs.id')
                ->select('players.first_name','players.last_name','players.number','players.price','players.position','players.club_id','players.id',
                        'clubs.name',(DB::raw('SUM(round_player.total) as total')))
                ->where([
                            ['players.position','=','GK'],
                            ['clubs.league_id','=',$request->l_id],                            
                        ])
                ->groupBy('players.id','players.first_name','players.last_name','players.number','players.price','players.position','players.club_id','clubs.name')
                ->orderBy('total','desc')                
                ->take(1)
                ->get();


        $def = DB::table('players')
                ->join('round_player','players.id','=','round_player.player_id')
                ->join('clubs','players.club_id','=','clubs.id')
                ->select('players.first_name','players.last_name','players.number','players.price','players.position','players.club_id','players.id',
                        (DB::raw('SUM(round_player.total) as total')),'clubs.name')
                ->where([
                            ['round_player.round_id','=',$round->id],
                            ['players.position','=','DEF'],
                            ['clubs.league_id','=',$request->l_id],
                        ])
                ->groupBy('players.id','players.first_name','players.last_name','players.number','players.price','players.position','players.club_id','clubs.name')                        
                ->orderBy('total','desc')
                ->take(4)
                ->get();

        $def_t = DB::table('players')
                ->join('round_player','players.id','=','round_player.player_id')
                ->join('clubs','players.club_id','=','clubs.id')
                ->select('players.first_name','players.last_name','players.number','players.price','players.position','players.club_id','players.id',
                        'clubs.name',(DB::raw('SUM(round_player.total) as total')))
                ->where([
                            ['players.position','=','DEF'],
                            ['clubs.league_id','=',$request->l_id],                            
                        ])
                ->groupBy('players.id','players.first_name','players.last_name','players.number','players.price','players.position','players.club_id','clubs.name')
                ->orderBy('total','desc')                                
                ->take(4)
                ->get();
                
        $mid = DB::table('players')
                ->join('round_player','players.id','=','round_player.player_id')
                ->join('clubs','players.club_id','=','clubs.id')
                ->select('players.first_name','players.last_name','players.number','players.price','players.position','players.club_id','players.id',
                        (DB::raw('SUM(round_player.total) as total')),'clubs.name')
                ->where([
                            ['round_player.round_id','=',$round->id],
                            ['players.position','=','MID'],
                            ['clubs.league_id','=',$request->l_id],                            
                        ])
                ->groupBy('players.id','players.first_name','players.last_name','players.number','players.price','players.position','players.club_id','clubs.name')                        
                ->orderBy('total','desc')
                ->take(4)
                ->get();

        $mid_t = DB::table('players')
                ->join('round_player','players.id','=','round_player.player_id')
                ->join('clubs','players.club_id','=','clubs.id')
                ->select('players.first_name','players.last_name','players.number','players.price','players.position','players.club_id','players.id',
                        'clubs.name',(DB::raw('SUM(round_player.total) as total')))
                ->where([
                            ['players.position','=','MID'],
                            ['clubs.league_id','=',$request->l_id],                            
                        ])
                ->groupBy('players.id','players.first_name','players.last_name','players.number','players.price','players.position','players.club_id','clubs.name')
                ->orderBy('total','desc')                            
                ->take(4)
                ->get();

        $atk = DB::table('players')
                ->join('round_player','players.id','=','round_player.player_id')
                ->join('clubs','players.club_id','=','clubs.id')
                ->select('players.first_name','players.last_name','players.number','players.price','players.position','players.club_id','players.id',
                         (DB::raw('SUM(round_player.total) as total')),'clubs.name')
                ->where([
                            ['round_player.round_id','=',$round->id],
                            ['players.position','=','ATK'],
                            ['clubs.league_id','=',$request->l_id],                            
                        ])
                ->groupBy('players.id','players.first_name','players.last_name','players.number','players.price','players.position','players.club_id','clubs.name')                        
                ->orderBy('total','desc')
                ->take(2)
                ->get();

        $atk_t = DB::table('players')
                ->join('round_player','players.id','=','round_player.player_id')
                ->join('clubs','players.club_id','=','clubs.id')
                ->select('players.first_name','players.last_name','players.number','players.price','players.position','players.club_id','players.id',
                        'clubs.name',(DB::raw('SUM(round_player.total) as total')))
                ->where([
                            ['players.position','=','ATK'],
                            ['clubs.league_id','=',$request->l_id],                            
                        ])
                ->groupBy('players.id','players.first_name','players.last_name','players.number','players.price','players.position','players.club_id','clubs.name')
                ->orderBy('total','desc')                            
                ->take(2)
                ->get();

        $results = [
            "previous" => [
                "gk"    => $gk,
                "def"   => $def,
                "mid"   => $mid,
                "atk"   => $atk
            ],
            "total" => [
                "gk"    => $gk_t,
                "def"   => $def_t,
                "mid"   => $mid_t,
                "atk"   => $atk_t
            ]
        ];
                
        if ($results === null) {
            $response = 'There was a problem fetching your data.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

    /**
     * Get single article
     *
     * Getter for an article // params: slug (slug of the article in database)
     * 
     */ 
    public function getArticle($slug)
    {
        $article = Article::where('slug',$slug)->first();
        if ($article === null) {
            $response = 'There was a problem fetching your data.';
            return $this->json($response, 404);
        }
        return $this->json($article);
    }

    /**
     * Get authorized user's squad points
     *
     * Getter for user's squad points by rounds (jwt auth token required) // params: l_id (league id)
     * 
     */ 
    public function getAllPoints(Request $request)
    {
        $user = JWTAuth::authenticate();
        $team = Squad::where('user_id',$user->id)->where('league_id',$request->l_id)->first();
        $starting = json_decode($team->selected_team);
        $subs = json_decode($team->substitutions);
        $starting_arr = array_values(json_decode(json_encode($starting), true));
        $subs_arr = array_values(json_decode(json_encode($subs), true));
        $league = League::where('id',$request->l_id)->first();
        $current_round = intval($league->current_round);
        // if($prev >1){
        //     $prev = $prev-1;
        // }
        $round_no = intval($request->gw);
        $round = Round::where('league_id',$request->l_id)->where('round_no',$round_no)->first();        

        if($current_round == $round_no){

        $st = DB::table('players')
            ->join('round_player','players.id','=','round_player.player_id')
            ->join('clubs','players.club_id','=','clubs.id')
            ->select('players.id','players.first_name','players.last_name','players.number','players.position','players.price','players.club_id',
            DB::raw('SUM(round_player.assist) as assist'),
            DB::raw('SUM(round_player.captain) as captain'),
            DB::raw('SUM(round_player.clean) as clean'),
            DB::raw('SUM(round_player.kd_3strike) as kd_3strike'),
            DB::raw('SUM(round_player.k_save) as k_save'),
            DB::raw('SUM(round_player.miss) as miss'),
            DB::raw('SUM(round_player.own_goal) as own_goal'),
            DB::raw('SUM(round_player.red) as red'),            
            DB::raw('SUM(round_player.yellow) as yellow'),            
            DB::raw('SUM(round_player.score) as score'),            
            DB::raw('SUM(round_player.start) as start'),            
            DB::raw('SUM(round_player.sub) as sub'),          
            DB::raw('SUM(round_player.total) as total'),                        
                'clubs.name as club_name','round_player.round_id')
           ->whereIn('players.id',$starting)
           ->where('round_player.round_id','=',$round->id)
           ->groupBy('players.id','round_player.round_id','players.first_name','players.last_name','players.number','players.position','players.price','players.club_id','club_name','round_player.round_id')
           ->orderBy('players.position','desc')
           ->get();

            $subs_ = implode(',', $subs_arr);
            $su = DB::table('players')
                    ->join('round_player','players.id','=','round_player.player_id')
                    ->join('clubs','players.club_id','=','clubs.id')
                    ->select('players.id','players.first_name','players.last_name','players.number','players.position','players.price','players.club_id',
                    DB::raw('SUM(round_player.assist) as assist'),
                    DB::raw('SUM(round_player.captain) as captain'),
                    DB::raw('SUM(round_player.clean) as clean'),
                    DB::raw('SUM(round_player.kd_3strike) as kd_3strike'),
                    DB::raw('SUM(round_player.k_save) as k_save'),
                    DB::raw('SUM(round_player.miss) as miss'),
                    DB::raw('SUM(round_player.own_goal) as own_goal'),
                    DB::raw('SUM(round_player.red) as red'),            
                    DB::raw('SUM(round_player.yellow) as yellow'),            
                    DB::raw('SUM(round_player.score) as score'),            
                    DB::raw('SUM(round_player.start) as start'),            
                    DB::raw('SUM(round_player.sub) as sub'),          
                    DB::raw('SUM(round_player.total) as total'),                        
                        'clubs.name as club_name','round_player.round_id')
                    ->where('round_player.round_id','=',$round->id)
                    ->groupBy('players.id','round_player.round_id','players.first_name','players.last_name','players.number','players.position','players.price','players.club_id','club_name','round_player.round_id')
                    ->whereIn('players.id',$subs)
                    ->orderByRaw(DB::raw("FIELD(players.id,$subs_)"))
                    ->get();
        }else{
            if($team->rounds()->where('squad_round.round_no',$round_no)->first()!==null){
                $sq = $team->rounds()->where('squad_round.round_no',$round_no)->first();
                $starting_arr = json_decode($sq->pivot->selected_team);
                $subs_arr =  json_decode($sq->pivot->substitutions);
    
                $st = DB::table('players')
                    ->join('round_player','players.id','=','round_player.player_id')
                    ->join('clubs','players.club_id','=','clubs.id')
                    ->select('players.id','players.first_name','players.last_name','players.number','players.position','players.price','players.club_id',
                    DB::raw('SUM(round_player.assist) as assist'),
                    DB::raw('SUM(round_player.captain) as captain'),
                    DB::raw('SUM(round_player.clean) as clean'),
                    DB::raw('SUM(round_player.kd_3strike) as kd_3strike'),
                    DB::raw('SUM(round_player.k_save) as k_save'),
                    DB::raw('SUM(round_player.miss) as miss'),
                    DB::raw('SUM(round_player.own_goal) as own_goal'),
                    DB::raw('SUM(round_player.red) as red'),            
                    DB::raw('SUM(round_player.yellow) as yellow'),            
                    DB::raw('SUM(round_player.score) as score'),            
                    DB::raw('SUM(round_player.start) as start'),            
                    DB::raw('SUM(round_player.sub) as sub'),          
                    DB::raw('SUM(round_player.total) as total'),                        
                        'clubs.name as club_name','round_player.round_id')
                    ->whereIn('players.id',$starting_arr)
                    ->where('round_player.round_id','=',$round->id)
                    ->groupBy('players.id','round_player.round_id','players.first_name','players.last_name','players.number','players.position','players.price','players.club_id','club_name','round_player.round_id')
                    ->orderBy('players.position','desc')
                    ->get();
    
                $subs_ = implode(',', $subs_arr);
                $su = DB::table('players')
                    ->join('round_player','players.id','=','round_player.player_id')
                    ->join('clubs','players.club_id','=','clubs.id')
                    ->select('players.id','players.first_name','players.last_name','players.number','players.position','players.price','players.club_id',
                    DB::raw('SUM(round_player.assist) as assist'),
                    DB::raw('SUM(round_player.captain) as captain'),
                    DB::raw('SUM(round_player.clean) as clean'),
                    DB::raw('SUM(round_player.kd_3strike) as kd_3strike'),
                    DB::raw('SUM(round_player.k_save) as k_save'),
                    DB::raw('SUM(round_player.miss) as miss'),
                    DB::raw('SUM(round_player.own_goal) as own_goal'),
                    DB::raw('SUM(round_player.red) as red'),            
                    DB::raw('SUM(round_player.yellow) as yellow'),            
                    DB::raw('SUM(round_player.score) as score'),            
                    DB::raw('SUM(round_player.start) as start'),            
                    DB::raw('SUM(round_player.sub) as sub'),          
                    DB::raw('SUM(round_player.total) as total'),                        
                        'clubs.name as club_name','round_player.round_id')
                    ->whereIn('players.id',$subs)
                    ->where('round_player.round_id','=',$round->id)
                    ->groupBy('players.id','round_player.round_id','players.first_name','players.last_name','players.number','players.position','players.price','players.club_id','club_name','round_player.round_id')
                    ->orderByRaw(DB::raw("FIELD(players.id,$subs_)"))
                    ->get();
                    // privremeno resenje
            }else{
                $response = "No records for that round";
                return $this->json($response,404);

            }
        }


        $gk = 0;
        $started = 0;
        foreach($st as $s){
            if(($s->start==1 || $s->sub==1) && $s->position!="GK"){
                $started++;
            }
            elseif(($s->start==1 || $s->sub==1) && $s->position==="GK"){
                $gk=1;
            }
        }
        if($gk===0 && ($su[0]->start=="1" || $su[0]->sub=="1")){
                $gk = 2;
        }
        $subs = 10-$started;
        if($subs > 3){
            if($subs==10){
                $subs=0;
            }else{
                $subs = 3;
            }
        }

        // $subs = 3 - $subs;

        // $meta = $user->oneLeague($l_id)->first();
        $meta = $user->oneLeague($request->l_id)->first()->pivot;

        $total = $meta->points;

        $current = DB::table('squad_round')
                ->select('points')
                ->where([
                    ['round_no','=',$round_no],
                    ['squad_id','=',$team->id]
                ])
                ->get();

        // if($current_round >1 && $current_round< $league->number_of_rounds){
        //     $previous = DB::table('squad_round')
        //         ->select('points')
        //         ->where([
        //             ['round_no','=',$current_round-1],
        //             ['squad_id','=',$team->id]
        //         ])
        //         ->get(); 
        //     $next = DB::table('squad_round')
        //         ->select('points')
        //         ->where([
        //             ['round_no','=',$current_round+1],
        //             ['squad_id','=',$team->id]
        //         ])
        //         ->get();
        // }elseif($current_round == 1){
        //     $previous = null;
        //     $next = DB::table('squad_round')
        //         ->select('points')
        //         ->where([
        //             ['round_no','=',$current_round+1],
        //             ['squad_id','=',$team->id]
        //         ])
        //         ->get();
            
        // }elseif($current_round === $league->number_of_rounds){
        //     $next = null;
        //     $previous = DB::table('squad_round')
        //         ->select('points')
        //         ->where([
        //             ['round_no','=',$current_round-1],
        //             ['squad_id','=',$team->id]
        //         ])
        //         ->get();
        // }

        
        $results = [
            "selected_team" => $st,
            "substitutions" => $su,
            "total"         => $total,
            "current"       => $current,
            "captain_id"    => $team->captain_id,
            "gk"            => $gk,
            "subs"          => $subs

        ];


        if ($results === null) {
            $response = 'There was a problem updating your data.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

    /**
     * Get authorized user's ranking
     *
     * Getter for user's ranking points in current and last round (jwt auth token required) // params: l_id (league id)
     * 
     */ 
    public function getUserRank(Request $request)
    {
        $user = JWTAuth::authenticate();
        $meta = $user->oneLeague($request->l_id)->first()->pivot;
        $current_round = League::where('id',$request->l_id)->first()->current_round;
        $prev_round = $current_round-1;
        $squad = Squad::where('user_id',$user->id)->where('league_id',$request->l_id)->first();
        
        $results = DB::table('users')
                ->join('user_league','users.id','=','user_league.user_id')
                ->select('users.first_name','users.last_name','users.uuid','users.username',
                        'user_league.points','user_league.squad_id')
                ->where('user_league.league_id','=',$request->l_id)
                ->orderBy('user_league.points', 'desc')
                ->orderBy('users.username','asc')
                ->get();

        //this gets first position of points
        $i=0;
        foreach($results as $result)
        {
            $points[$i] = $result->points;
            $i++;
        }
        $key = array_search($meta->points,$points);
        $key++;

        $no_of_users = DB::table('user_league')
                        ->where('league_id','=',$request->l_id)
                        ->count();

        $previous = DB::table('squad_round')
                        ->select('points')
                        ->where([
                            ['round_no','=',$prev_round],
                            ['squad_id','=',$squad->id]
                        ])
                        ->get();
        
        $current = DB::table('squad_round')
                    ->select('points')
                    ->where([
                        ['round_no','=',$current_round],
                        ['squad_id','=',$squad->id]
                    ])
                    ->get();

        if(!isset($current[0])){
            $current = 0;
        }

        $results = [
            "position" => $key,
            "total_players" => $no_of_users,
            "last_round"    => $previous,
            "current"       => $current
        ];

        //this returns exact position in table
        // $i=0;
        // while(($results[$i]->uuid != $user->uuid))
        // {
        //     $i++;
        // }
    
        // return $i;

        if ($results === null) {
            $response = 'There was a problem fetching players.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

    /**
     * Get authorized user's ranking
     *
     * Getter for user's ranking points in current and last round (jwt auth token required) // params: l_id (league id)
     * 
     */ 
    public function getRankingTable($l_id)
    {
        $results = DB::table('users')
                ->join('user_league','users.id','=','user_league.user_id')
                ->select('users.first_name','users.last_name','users.uuid','users.username',
                        'user_league.points','user_league.squad_id')
                ->where('user_league.league_id','=',$l_id)
                ->orderBy('user_league.points', 'desc')
                ->get();

        if ($results === null) {
            $response = 'There was a problem fetching players.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

    /**
     * Send tip 
     *
     * Send tips(tickets) to admins in that league (jwt auth token required) // params: l_id (league id)
     * 
     */ 
    public function sendTip(Request $request)
    {
        $user = JWTAuth::authenticate();
        $subject = $request->subject;
        $body = $request->body;
        $league = League::where('id',$request->l_id)->first()->name;
        
        $tip = new Tip;
        $tip->subject = $request->subject;
        $tip->body = $request->body;
        $tip->league_id = $request->l_id;
        $tip->user_id = $user->id;
        $tip->save();

        $info = new User;
        $info->email = "info@breddefantasy.com"; 
        $info->first_name = $user->first_name;
        $info->last_name = $user->last_name;

        Mail::to("breddefantasy@gmail.com")->send(new SendTip($user,"$user->first_name $user->last_name has submitted a tip!",$request->body,"emails.tip_send",$league));
        Mail::to($user->email)->send(new SendTip($info,"You have just submitted a tip on breddefantasy.com!",$request->body,"emails.tip_confirm",$league));
        

        if ($tip === null) {
            $response = 'There was a problem fetching players.';
            return $this->json($response, 404);
        }
        return $this->json($tip);
    }

    /**
     * Check if username or email exists
     *
     * Used on registration, checking if user with username or email already exists  // params: username, email 
     * 
     */ 
    public function checkUser(Request $request)
    {
        $response_email = null;
        $response_username = null;

        $results = User::where('email',$request->email)->first();
        if ($results != null) {
            $response_email = 'There is a user with that email.';
        }
        $results = User::where('username',$request->username)->first();
        if ($results != null) {
            $response_username = 'There is a user with that username.';
        }
        $results = [
            "email" => $response_email,
            "username" => $response_username
        ];
        if ($results === null) {
            $response = 'ok';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

    /**
     * Get single user's ranking
     *
     * Getter for single user's ranking points in current,last round and avg on Ranking page // params: l_id (league id), uuid
     * 
     */ 
    public function getUserStats(Request $request)
    {
        $user = User::where('uuid',$request->uuid)->first();
        $squad = Squad::where('league_id',$request->l_id)->where('user_id',$user->id)->first();
        $prev_round = League::where('id',$request->l_id)->first()->current_round;
        $prev_round--;
        $stats = DB::table('users')
                ->join('user_league','users.id','=','user_league.user_id')
                ->select('user_league.points','users.created_at')
                ->where('users.id','=',$user->id)
                ->get();
        
        $previous = DB::table('squad_round')
                ->select('points')
                ->where([
                    ['round_no','=',$prev_round],
                    ['squad_id','=',$squad->id]
                ])
                ->get();

        $avg = DB::table('squad_round')
                ->where([
                    ['league_id','=',$request->l_id],
                    ['squad_id','=',$squad->id]
                ])
                ->avg('points');
        
        $results = [
            "current" => $stats,
            "previous" => $previous,
            "avg" => $avg
        ];

        if ($results === null) {
            $response = 'User does not exist';
            return $this->json($response, 404);
        }
        return $this->json($results);

    }

    /**
     * Get all user's ranking
     *
     * Getter for all users` ranking points in current,last round and avg on Ranking page // params: l_id (league id)
     * 
     */ 
    public function getUsersStats(Request $request)
    {
        $prev_round = League::where('id',$request->l_id)->first()->current_round;
        $prev_round--;
        $stats = DB::table('users')
                ->join('user_league','users.id','=','user_league.user_id')
                ->select('user_league.points','users.created_at')
                ->get();
        
        $previous = DB::table('squad_round')
                ->select('points')
                ->where([
                    ['round_no','=',$prev_round],
                    ['league_id','=',$request->l_id]
                ])
                ->get();

        $avg = DB::table('squad_round')
                ->select(DB::raw('AVG(squad_round.points) as avg'))
                ->where([
                    ['league_id','=',$request->l_id],
                ])
                ->groupBy('squad_id')
                ->get();
                
        
        $results = [
            "current" => $stats,
            "previous" => $previous,
            "avg" => $avg
        ];

        if ($results === null) {
            $response = 'User does not exist';
            return $this->json($response, 404);
        }
        return $this->json($results);

    }

    /**
     * Get all user's ranking
     *
     * Getter for all users` ranking points in current,last round and avg on Ranking page // params: l_id (league id)
     * 
     */ 
    public function getPlayerInfo(Request $request)
    {
        $player = Player::where('id',$request->id)->withTrashed()->first();
        $club = $player->club;
        $current_round = League::where('id',$club->league_id)->first()->current_round;
        // if($current_round > 1){
        //     $current_round--;
        // }
        if($current_round>1){
            $prev_round = $current_round-1;            
        }else{
            $prev_round = 1;
        }

        $c_round = Round::where('round_no',$current_round)->where('league_id',$club->league_id)->first();
        $p_round = Round::where('round_no',$prev_round)->where('league_id',$club->league_id)->first();
        $price = $player->price;
        $total = DB::table('players')
                ->join('round_player','players.id','=','round_player.player_id')
                ->select(DB::raw('SUM(round_player.total) as total'),'players.id')
                ->where('players.id','=',$request->id)
                ->groupBy('players.id')
                ->get();
        
        $current = DB::table('round_player')
                ->select(DB::raw('SUM(total) as total'))
                ->where([
                    ['player_id','=',$request->id],
                    ['round_id','=',$c_round->id]
                    ])
                ->groupBy('player_id')
                ->get();
        
        $avg = DB::table('round_player')
                ->where('player_id','=',$request->id)
                ->avg('total');

        $prev = DB::table('round_player')
                ->select(DB::raw('SUM(total) as total'))
                ->where([
                    ['player_id','=',$request->id],
                    ['round_id','=',$p_round->id]
                    ])
                ->groupBy('player_id')
                ->get();

        $gameweeks = DB::table('players')
                ->join('round_player','players.id','=','round_player.player_id')
                ->select('round_player.player_id','round_player.round_id',
                DB::raw('SUM(round_player.assist) as assist'),
                DB::raw('SUM(round_player.captain) as captain'),
                DB::raw('SUM(round_player.clean) as clean'),
                DB::raw('SUM(round_player.kd_3strike) as kd_3strike'),
                DB::raw('SUM(round_player.k_save) as k_save'),
                DB::raw('SUM(round_player.miss) as miss'),
                DB::raw('SUM(round_player.own_goal) as own_goal'),
                DB::raw('SUM(round_player.red) as red'),            
                DB::raw('SUM(round_player.yellow) as yellow'),            
                DB::raw('SUM(round_player.score) as score'),            
                DB::raw('SUM(round_player.start) as start'),            
                DB::raw('SUM(round_player.sub) as sub'),          
                DB::raw('SUM(round_player.total) as total')   )
                ->where('players.id','=',$player->id)
                ->groupBy('round_player.player_id','round_player.round_id')
                ->orderBy('round_player.round_id','asc')
                ->get();

        $i = 1;
        foreach($gameweeks as $gameweek){
            $gameweek->round_no = $i++;
        }

        $results = [
            "player" => $player,
            "total"  => $total,
            "current"=> $current,
            "prev"   => $prev,
            "avg"    => $avg,
            "price"  => $price,
            "round"  => $current_round,
            "gameweeks" => $gameweeks
        ];


        if ($results === null) {
            $response = 'User does not exist';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

    /**
     * Get single match statistics
     *
     * Getter for single match stats(score, assists, yellow and red cards) // params: l_id (league id), m_id (match id)
     * 
     */ 
    public function getMatchInfo(Request $request)
    {
        $match = Match::where('id', $request->m_id)->first();
        $club1 = Club::where('league_id',$request->l_id)->where('name', $match->club1_name)->first();
        $club2 = Club::where('league_id',$request->l_id)->where('name', $match->club2_name)->first();

        $clubs = array($club1->id , $club2->id);

        $players1 = DB::table('players')
                    ->join('clubs','players.club_id','=','clubs.id')
                    ->select('players.id')
                    ->where('clubs.id','=',$club1->id)
                    ->get();

        $players2 = DB::table('players')
                    ->join('clubs','players.club_id','=','clubs.id')
                    ->select('players.id')
                    ->where('clubs.id',$club2->id)
                    ->get();
        $players1 = array_values(json_decode(json_encode($players1), true)); 
        $players2 = array_values(json_decode(json_encode($players2), true)); 
    
        $player_ids1 = array();  
        foreach($players1 as $player){
            array_push($player_ids1,$player['id']);
        }
        

        $player_ids2 = array();  
        foreach($players2 as $player){
            array_push($player_ids2,$player['id']);
        }

                    
        $score1 = DB::table('players')
                ->join('round_player','players.id','=','round_player.player_id')
                ->select('players.first_name','players.last_name','players.id','players.number','players.position','round_player.score as ammount')
                ->whereIn('players.id',$player_ids1)  
                ->where([
                            // ['round_player.round_id','=',$request->r_id],
                            // ['round_player.round_id','=',$match->round_id],
                            ['round_player.match_id','=',$match->id],
                            // ['round_player.score', 'NOT', NULL],
                            ['round_player.score', '>', 0],
                        ])
                      
                ->get();

        $score2 = DB::table('players')
                ->join('round_player','players.id','=','round_player.player_id')
                ->select('players.first_name','players.last_name','players.id','players.number','players.position','round_player.score as ammount')
                ->whereIn('players.id',$player_ids2)  
                ->where([
                            // ['round_player.round_id','=',$request->r_id],
                            // ['round_player.round_id','=',$match->round_id],
                            ['round_player.match_id','=',$match->id],                            
                            // ['round_player.score', 'NOT', NULL],
                            ['round_player.score', '>', 0],
                        ])
                      
                ->get();


        $assists1 = DB::table('players')
                ->join('round_player','players.id','=','round_player.player_id')
                ->select('players.first_name','players.last_name','players.id','players.number','players.position','round_player.assist as ammount')
                ->whereIn('players.id',$player_ids1)  
                ->where([
                            // ['round_player.round_id','=',$request->r_id],
                            // ['round_player.round_id','=',$match->round_id],
                            ['round_player.match_id','=',$match->id],                            
                            // ['round_player.score', 'NOT', NULL],
                            ['round_player.assist', '>', 0],
                        ])
                    
                ->get();

        $assists2 = DB::table('players')
                ->join('round_player','players.id','=','round_player.player_id')
                ->select('players.first_name','players.last_name','players.id','players.number','players.position','round_player.assist as ammount')
                ->whereIn('players.id',$player_ids2)  
                ->where([
                            // ['round_player.round_id','=',$request->r_id],
                            // ['round_player.round_id','=',$match->round_id],
                            ['round_player.match_id','=',$match->id],                            
                            // ['round_player.score', 'NOT', NULL],
                            ['round_player.assist', '>', 0],
                        ])
                    
                ->get();

        $yellow1 = DB::table('players')
                ->join('round_player','players.id','=','round_player.player_id')
                ->select('players.first_name','players.last_name','players.id','players.number','players.position','round_player.yellow as ammount')
                ->whereIn('players.id',$player_ids1)  
                ->where([
                            // ['round_player.round_id','=',$request->r_id],
                            // ['round_player.round_id','=',$match->round_id],
                            ['round_player.match_id','=',$match->id],                            
                            // ['round_player.score', 'NOT', NULL],
                            ['round_player.yellow', '>', 0],
                        ])
                    
                ->get();

        $yellow2 = DB::table('players')
                ->join('round_player','players.id','=','round_player.player_id')
                ->select('players.first_name','players.last_name','players.id','players.number','players.position','round_player.yellow as ammount')
                ->whereIn('players.id',$player_ids2)  
                ->where([
                            // ['round_player.round_id','=',$request->r_id],
                            // ['round_player.round_id','=',$match->round_id],
                            ['round_player.match_id','=',$match->id],                            
                            // ['round_player.score', 'NOT', NULL],
                            ['round_player.yellow', '>', 0],
                        ])
                    
                ->get();

        $red1 = DB::table('players')
                ->join('round_player','players.id','=','round_player.player_id')
                ->select('players.first_name','players.last_name','players.id','players.number','players.position','round_player.red as ammount')
                ->whereIn('players.id',$player_ids1)  
                ->where([
                            // ['round_player.round_id','=',$request->r_id],
                            // ['round_player.round_id','=',$match->round_id],
                            ['round_player.match_id','=',$match->id],                            
                            // ['round_player.score', 'NOT', NULL],
                            ['round_player.red', '>', 0],
                        ])
                    
                ->get();

        $red2 = DB::table('players')
                ->join('round_player','players.id','=','round_player.player_id')
                ->select('players.first_name','players.last_name','players.id','players.number','players.position','round_player.red as ammount')
                ->whereIn('players.id',$player_ids2)  
                ->where([
                            // ['round_player.round_id','=',$request->r_id],
                            // ['round_player.round_id','=',$match->round_id],
                            ['round_player.match_id','=',$match->id],                            
                            // ['round_player.score', 'NOT', NULL],
                            ['round_player.red', '>', 0],
                        ])
                    
                ->get();

        $clean_sheat1 = DB::table('players')
                ->join('round_player','players.id','=','round_player.player_id')
                ->select('players.first_name','players.last_name','players.id','players.number','players.position','round_player.clean as ammount')
                ->whereIn('players.id',$player_ids1)  
                ->where([
                            // ['round_player.round_id','=',$request->r_id],
                            // ['round_player.round_id','=',$match->round_id],
                            ['round_player.match_id','=',$match->id],                            
                            // ['round_player.score', 'NOT', NULL],
                            ['round_player.clean', '>', 0],
                        ])
                    
                ->get();

        $clean_sheat2 = DB::table('players')
                ->join('round_player','players.id','=','round_player.player_id')
                ->select('players.first_name','players.last_name','players.id','players.number','players.position','round_player.clean as ammount')
                ->whereIn('players.id',$player_ids2)  
                ->where([
                            // ['round_player.round_id','=',$request->r_id],
                            // ['round_player.round_id','=',$match->round_id],
                            ['round_player.match_id','=',$match->id],                            
                            // ['round_player.score', 'NOT', NULL],
                            ['round_player.clean', '>', 0],
                        ])
                    
                ->get();

        $missed_penal1 =DB::table('players')
                ->join('round_player','players.id','=','round_player.player_id')
                ->select('players.first_name','players.last_name','players.id','players.number','players.position','players.position','round_player.miss as ammount')
                ->whereIn('players.id',$player_ids1)  
                ->where([
                            // ['round_player.round_id','=',$request->r_id],
                            // ['round_player.round_id','=',$match->round_id],
                            ['round_player.match_id','=',$match->id],                            
                            // ['round_player.score', 'NOT', NULL],
                            ['round_player.miss', '>', 0],
                        ])
                    
                ->get();

        $missed_penal2 =DB::table('players')
                ->join('round_player','players.id','=','round_player.player_id')
                ->select('players.first_name','players.last_name','players.id','players.number','players.position','players.position','round_player.miss as ammount')
                ->whereIn('players.id',$player_ids2)  
                ->where([
                            // ['round_player.round_id','=',$request->r_id],
                            // ['round_player.round_id','=',$match->round_id],
                            ['round_player.match_id','=',$match->id],                            
                            // ['round_player.score', 'NOT', NULL],
                            ['round_player.miss', '>', 0],
                        ])
                    
                ->get();

        $saved_penal1 =DB::table('players')
                ->join('round_player','players.id','=','round_player.player_id')
                ->select('players.first_name','players.last_name','players.id','players.number','players.position','players.position','round_player.k_save as ammount')
                ->whereIn('players.id',$player_ids1)  
                ->where([
                            // ['round_player.round_id','=',$request->r_id],
                            // ['round_player.round_id','=',$match->round_id],
                            ['round_player.match_id','=',$match->id],                            
                            // ['round_player.score', 'NOT', NULL],
                            ['round_player.k_save', '>', 0],
                        ])
                    
                ->get();

        $saved_penal2 =DB::table('players')
                ->join('round_player','players.id','=','round_player.player_id')
                ->select('players.first_name','players.last_name','players.id','players.number','players.position','players.position','round_player.k_save as ammount')
                ->whereIn('players.id',$player_ids2)  
                ->where([
                            // ['round_player.round_id','=',$request->r_id],
                            // ['round_player.round_id','=',$match->round_id],
                            ['round_player.match_id','=',$match->id],                            
                            // ['round_player.score', 'NOT', NULL],
                            ['round_player.k_save', '>', 0],
                        ])
                    
                ->get();

        $results = [
            "club1" => [
                "score" => $score1,
                "assist" => $assists1,
                "yellow" => $yellow1,
                "red"   => $red1,
                // "clean_sheat" => $clean_sheat1,
                // "missed"    =>  $missed_penal1,
                // "saved" =>  $saved_penal1
            ],
            "club2" =>  [
                "score" => $score2,
                "assist" => $assists2,
                "yellow" => $yellow2,
                "red"   => $red2,
                // "clean_sheat" => $clean_sheat2,
                // "missed"    =>  $missed_penal2,
                // "saved" =>  $saved_penal2
            ]
        ];

        if ($results === null) {
            $response = 'User does not exist';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }


    
    
}
