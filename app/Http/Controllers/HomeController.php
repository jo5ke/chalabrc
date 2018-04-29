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

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
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

    public function getAllLeagues()
    {
        $results = League::all();
        if ($results === null) {
            $response = 'There was a problem fetching leagues.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

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

    public function getAllNews()
    {
        $results = Article::where('public',1)->get();
        if ($results === null) {
            $response = 'There was a problem fetching news.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

    public function getNewsByLeague(Request $request)
    {
        $results = Article::where('league_id', $request->l_id)->orderBy('created_at','desc')->paginate(3);
        if ($results === null) {
            $response = 'There was a problem fetching news.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

    public function getLatestNews(Request $request)
    {
        $results = Article::where('league_id',$request->l_id)->orderBy('created_at', 'desc')->first();
 
        if ($results === null) {
            $response = 'There was a problem fetching news.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

    public function getTopFivePlayers()
    {
        $results = DB::table('users')
                ->join('user_league','users.id','=','user_league.user_id')
                ->select('users.first_name','users.last_name','users.uuid','users.username',
                         'user_league.points','user_league.squad_id')
                ->orderBy('user_league.points', 'desc')
                ->take(5)
                ->get();

       // $results = User::orderBy('points', 'desc')->take(5)->get();
        if ($results === null) {
            $response = 'There was a problem fetching players.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

    // "id" = id lige (1 i 2)
    public function topFivePlayersDivision(Request $request)
    {
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

    public function topFivePlayersDivision1()
    {
        $results = DB::table('users')
                    ->join('user_league','users.id','=','user_league.user_id')
                    ->select('users.first_name','users.last_name','users.uuid','users.username',
                            'user_league.points','user_league.squad_id')
                    ->where('user_league.league_id','=',1)
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

    public function topFivePlayersDivision2(Request $request)
    {
        $results = DB::table('users')
                    ->join('user_league','users.id','=','user_league.user_id')
                    ->select('users.first_name','users.last_name','users.uuid','users.username',
                            'user_league.points','user_league.squad_id')
                    ->where('user_league.league_id','=',2)
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

    public function getJersey(Request $request)
    {
        // $file = Storage::disk('public')->get($request->name);
        // return new Response($file, 200);
        if(Storage::disk('public')->exists($request->name)){
            $response = Response::make(Storage::disk('public')->get($request->name));
        }else{
            $response = Response::make(Storage::disk('public')->get('dzoniDefault.png'));
        }
        $response->header('Content-Type', 'image/png');
        return $response;
    }

    // with i argument???
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

        if($this->term==""){
            $results = DB::table('users')
                    ->join('user_league','users.id','=','user_league.user_id')
                    ->join('squads','users.id','=','squads.user_id')
                    ->join('squad_round','squads.id','=','squad_round.squad_id')
                    ->select('users.first_name','users.last_name','users.uuid','users.created_at','users.username',
                            'user_league.money','user_league.points','user_league.squad_id','squad_round.points as prev_round')
                    ->where('squad_round.round_no','=',$current_round)
                    ->where('user_league.league_id','=',$request->l_id)
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

    public function getUserPointsPerWeek(Request $request)
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

    public function viewFile($name)
    {
        return response()->make(Storage::get($name), 200, [
            'Content-Type' => Storage::mimeType($name),
            'Content-Disposition' => 'inline; '.$name,
        ]);
    }
    
    //l_id,uuid
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

    // invalid function
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
                        'round_player.total','clubs.name')
                ->where([
                            ['round_player.round_id','=',$round->id],
                            ['players.position','=','GK'],
                            ['clubs.league_id','=',$request->l_id],                            
                        ])
                ->orderBy('round_player.total','desc')
                ->take(1)
                ->get();

        $def = DB::table('players')
                ->join('round_player','players.id','=','round_player.player_id')
                ->join('clubs','players.club_id','=','clubs.id')
                ->select('players.first_name','players.last_name','players.number','players.price','players.position','players.club_id','players.id',
                        'round_player.total','clubs.name')
                ->where([
                            ['round_player.round_id','=',$round->id],
                            ['players.position','=','DEF'],
                            ['clubs.league_id','=',$request->l_id],
                        ])
                ->orderBy('round_player.total','desc')
                ->take(4)
                ->get();
                
        $mid = DB::table('players')
                ->join('round_player','players.id','=','round_player.player_id')
                ->join('clubs','players.club_id','=','clubs.id')
                ->select('players.first_name','players.last_name','players.number','players.price','players.position','players.club_id','players.id',
                        'round_player.total','clubs.name')
                ->where([
                            ['round_player.round_id','=',$round->id],
                            ['players.position','=','MID'],
                            ['clubs.league_id','=',$request->l_id],                            
                        ])
                ->orderBy('round_player.total','desc')
                ->take(4)
                ->get();

        $atk = DB::table('players')
                ->join('round_player','players.id','=','round_player.player_id')
                ->join('clubs','players.club_id','=','clubs.id')
                ->select('players.first_name','players.last_name','players.number','players.price','players.position','players.club_id','players.id',
                        'round_player.total','clubs.name')
                ->where([
                            ['round_player.round_id','=',$round->id],
                            ['players.position','=','ATK'],
                            ['clubs.league_id','=',$request->l_id],                            
                        ])
                ->orderBy('round_player.total','desc')
                ->take(2)
                ->get();

        $results = [
            "gk"    => $gk,
            "def"   => $def,
            "mid"   => $mid,
            "atk"   => $atk
        ];
                
        if ($results === null) {
            $response = 'There was a problem fetching your data.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

    public function getArticle($slug)
    {
        $article = Article::where('slug',$slug)->first();
        if ($article === null) {
            $response = 'There was a problem fetching your data.';
            return $this->json($response, 404);
        }
        return $this->json($article);
    }

    // public function getUserSettings()
    // {
    //     $user = JWTAuth::authenticate();
    //     $results = [
    //         "first_name" => $user->first_name,
    //         "last_name"  => $user->last_name,
    //         "city"       => $user->city,
    //         "country"    => $user->country,
    //         "birthdate"  => $user->birthdate   
    //     ];

    //     if ($results === null) {
    //         $response = 'There was a problem updating your data.';
    //         return $this->json($response, 404);
    //     }
    //     return $this->json($results);
    // }

    // public function updateUserSettings(Request $request)
    // {
    //     $user = JWTAuth::authenticate();
    //     $user->birthdate = $request->birthdate;
    //     $user->country = $request->country;
    //     $user->city = $request->city;
    //     $user->first_name = ucwords($request->first_name);
    //     $user->last_name = ucwords($request->last_name);
    //     // $full_name = $request->full_name;
    //     // $full_name = explode(" ",$full_name);
    //     $user->save();

    //     if ($user === null) {
    //         $response = 'There was a problem updating your data.';
    //         return $this->json($response, 404);
    //     }
    //     return $this->json($user);
    // }

    public function getAllPoints(Request $request)
    {
        $user = JWTAuth::authenticate();
        $team = Squad::where('user_id',$user->id)->where('league_id',$request->l_id)->first();
        $starting = json_decode($team->selected_team);
        $subs = json_decode($team->substitutions);
        $starting_arr = array_values(json_decode(json_encode($starting), true));
        $subs_arr = array_values(json_decode(json_encode($subs), true));
        $league = League::where('id',$request->l_id)->first();
        $current_round = $league->current_round;
        // if($prev >1){
        //     $prev = $prev-1;
        // }
        $round_no = intval($request->gw);
        $round = Round::where('league_id',$request->l_id)->where('round_no',$round_no)->first();        

        if($current_round === $round_no){
            $st = DB::table('players')
            ->join('round_player','players.id','=','round_player.player_id')
            ->join('clubs','players.club_id','=','clubs.id')
            ->select('clubs.name as club_name','players.first_name','players.last_name','players.id','players.number','players.position','players.price','players.club_id',
                   'round_player.assist','round_player.captain','round_player.clean','round_player.kd_3strike','k_save','round_player.miss',
                   'round_player.own_goal','round_player.player_id','round_player.red','round_player.yellow','round_player.round_id','round_player.score','round_player.start','round_player.sub','round_player.total')
           ->where('round_player.round_id','=',$round->id)
           ->whereIn('players.id',$starting)
           ->orderBy('players.position','desc')
           ->get();

            $subs_ = implode(',', $subs_arr);
            $su = DB::table('players')
                    ->join('round_player','players.id','=','round_player.player_id')
                    ->join('clubs','players.club_id','=','clubs.id')
                    ->select('clubs.name as club_name','players.first_name','players.last_name','players.id','players.number','players.position','players.price','players.club_id',
                            'round_player.assist','round_player.captain','round_player.clean','round_player.kd_3strike','k_save','round_player.miss',
                            'round_player.own_goal','round_player.player_id','round_player.red','round_player.yellow','round_player.round_id','round_player.score','round_player.start','round_player.sub','round_player.total')
                    ->where('round_player.round_id','=',$round->id)
                    ->orderByRaw(DB::raw("FIELD(players.id,$subs_)"))
                    ->whereIn('players.id',$subs)
                    ->get();
        }else{
            if($team->rounds()->where('squad_round.round_no',$round_no)->first()!==null){
                $sq = $team->rounds()->where('squad_round.round_no',$round_no)->first();
                $starting_arr = json_decode($sq->pivot->selected_team);
                $subs_arr =  json_decode($sq->pivot->substitutions);
    
                $st = DB::table('players')
                    ->join('round_player','players.id','=','round_player.player_id')
                    ->join('clubs','players.club_id','=','clubs.id')
                    ->select('clubs.name as club_name','players.first_name','players.last_name','players.id','players.number','players.position','players.price','players.club_id',
                        'round_player.assist','round_player.captain','round_player.clean','round_player.kd_3strike','k_save','round_player.miss',
                        'round_player.own_goal','round_player.player_id','round_player.red','round_player.yellow','round_player.round_id','round_player.score','round_player.start','round_player.sub','round_player.total')
                    ->where('round_player.round_id','=',$round->id)
                    ->whereIn('players.id',$starting_arr)
                    ->orderBy('players.position','desc')
                    ->get();
    
                $subs_ = implode(',', $subs_arr);
                $su = DB::table('players')
                    ->join('round_player','players.id','=','round_player.player_id')
                    ->join('clubs','players.club_id','=','clubs.id')
                    ->select('clubs.name as club_name','players.first_name','players.last_name','players.id','players.number','players.position','players.price','players.club_id',
                            'round_player.assist','round_player.captain','round_player.clean','round_player.kd_3strike','k_save','round_player.miss',
                            'round_player.own_goal','round_player.player_id','round_player.red','round_player.yellow','round_player.round_id','round_player.score','round_player.start','round_player.sub','round_player.total')
                    ->where('round_player.round_id','=',$round->id)
                    ->orderByRaw(DB::raw("FIELD(players.id,$subs_)"))
                    ->whereIn('players.id',$subs)
                    ->get();
                    // privremeno resenje
            }else{
                $response = "No records for that round";
                return $this->json($response,404);
            //     $st = DB::table('players')
            //     ->join('round_player','players.id','=','round_player.player_id')
            //     ->join('clubs','players.club_id','=','clubs.id')
            //     ->select('clubs.name as club_name','players.first_name','players.last_name','players.id','players.number','players.position','players.price','players.club_id',
            //            'round_player.assist','round_player.captain','round_player.clean','round_player.kd_3strike','k_save','round_player.miss',
            //            'round_player.own_goal','round_player.player_id','round_player.red','round_player.yellow','round_player.round_id','round_player.score','round_player.start','round_player.sub','round_player.total')
            //    ->where('round_player.round_id','=',$round->id)
            //    ->whereIn('players.id',$starting)
            //    ->orderBy('players.position','desc')
            //    ->get();
    
            //     $subs_ = implode(',', $subs_arr);
            //     $su = DB::table('players')
            //             ->join('round_player','players.id','=','round_player.player_id')
            //             ->join('clubs','players.club_id','=','clubs.id')
            //             ->select('clubs.name as club_name','players.first_name','players.last_name','players.id','players.number','players.position','players.price','players.club_id',
            //                     'round_player.assist','round_player.captain','round_player.clean','round_player.kd_3strike','k_save','round_player.miss',
            //                     'round_player.own_goal','round_player.player_id','round_player.red','round_player.yellow','round_player.round_id','round_player.score','round_player.start','round_player.sub','round_player.total')
            //             ->where('round_player.round_id','=',$round->id)
            //             ->orderByRaw(DB::raw("FIELD(players.id,$subs_)"))
            //             ->whereIn('players.id',$subs)
            //             ->get();

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

        //this gets back exact position in table
        // $i=0;
        // while(($results[$i]->uuid != $user->uuid))
        // {
        //     $i++;
        //     $kralj = "ostaje kralj";
        // }
    
        // return $i;

        if ($results === null) {
            $response = 'There was a problem fetching players.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

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

    public function getPlayerInfo(Request $request)
    {
        $player = Player::where('id',$request->id)->first();
        $club = $player->club;
        $current_round = League::where('id',$club->league_id)->first()->current_round;
        // if($current_round > 1){
        //     $current_round--;
        // }
        $prev_round = $current_round-1;

        $price = $player->price;
        $total = DB::table('players')
                ->join('round_player','players.id','=','round_player.player_id')
                ->select(DB::raw('SUM(round_player.total) as total'))
                ->where('players.id','=',$request->id)
                ->groupBy('players.id')
                ->get();
        
        $current = DB::table('round_player')
                ->select('total')
                ->where([
                    ['player_id','=',$request->id],
                    ['round_id','=',$current_round]
                    ])
                ->get();
        
        $avg = DB::table('round_player')
                ->where('player_id','=',$request->id)
                ->avg('total');

        $prev = DB::table('round_player')
                ->select('total')
                ->where([
                    ['player_id','=',$request->id],
                    ['round_id','=',$prev_round]
                    ])
                ->get();

        $gameweeks = DB::table('players')
                ->join('round_player','players.id','=','round_player.player_id')
                ->select('round_player.assist','round_player.captain','round_player.clean','round_player.kd_3strike','round_player.k_save','round_player.miss',
                'round_player.own_goal','round_player.player_id','round_player.red','round_player.yellow','round_player.round_id','round_player.score','round_player.start','round_player.sub','round_player.total')
                ->where('players.id','=',$player->id)
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
                            ['round_player.round_id','=',$match->round_id],
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
                            ['round_player.round_id','=',$match->round_id],
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
                            ['round_player.round_id','=',$match->round_id],
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
                            ['round_player.round_id','=',$match->round_id],
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
                            ['round_player.round_id','=',$match->round_id],
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
                            ['round_player.round_id','=',$match->round_id],
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
                            ['round_player.round_id','=',$match->round_id],
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
                            ['round_player.round_id','=',$match->round_id],
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
                            ['round_player.round_id','=',$match->round_id],
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
                            ['round_player.round_id','=',$match->round_id],
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
                            ['round_player.round_id','=',$match->round_id],
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
                            ['round_player.round_id','=',$match->round_id],
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
                            ['round_player.round_id','=',$match->round_id],
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
                            ['round_player.round_id','=',$match->round_id],
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
