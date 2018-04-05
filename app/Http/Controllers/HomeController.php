<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Article as Article;
use App\User as User;
use App\League as League;
use App\Tip as Tip;
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
        $results = Article::all();
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
        // $results = User::with(['oneLeague' => $request->l_id])->get();
        // $users = User::all();
        // $results = $users->oneLeague($request->l_id)->get();
        $league = League::where('id',$request->l_id)->first();
        $prev_round = $league->current_round-1;
        $this->term = $request->term ? $request->term : "";
        $per_page = $request->per_page ? $request->per_page : null;

        if($this->term==""){
            $results = DB::table('users')
                    ->join('user_league','users.id','=','user_league.user_id')
                    ->select('users.first_name','users.last_name','users.uuid','users.created_at','users.username',
                            'user_league.money','user_league.points','user_league.squad_id')
                    ->where('user_league.league_id','=',$request->l_id)
                    ->orderBy('user_league.points','desc')
                    ->orderBy('users.username','asc');
                    // ->take(10)
                    // ->get();
        }else{
            $this->term = $this->term . "%";
            $results = DB::table('users')
                    ->join('user_league','users.id','=','user_league.user_id')
                    ->select('users.first_name','users.last_name','users.uuid','users.created_at','users.username',
                            'user_league.money','user_league.points','user_league.squad_id')
                    // ->where([
                    //             ['user_league.league_id','=',$request->l_id],
                    //             ['users.first_name','LIKE',$term]
                    //          ])
                    ->where('user_league.league_id','=',$request->l_id)
                    ->where(function ($query) {
                                $query->where('users.first_name','LIKE',$this->term)
                                ->orWhere('users.last_name','LIKE',$this->term);
                    })
                        
                    ->orderBy('user_league.points','desc')
                    ->orderBy('users.username','asc');
                    // ->take(10)
                    // ->get();
        }
        if($per_page===null){
            $results = $results->take(11)->get();
        }else{
            $results = $results->paginate($per_page);
        }

        // $previous = DB::table('squad_round')
        //     ->select('points')
        //     ->where([
        //         ['round_no','=',$prev_round]
        //     ])
        //     ->get();

        // $avg = DB::table('squad_round')
        //     ->where([
        //         ['league_id','=',$request->l_id],
        //     ])
        //     ->groupBy('squad_id')
        //     ->avg('points');



        // $res = [
        //     "users" => $results,
        //     "previous" => $previous,
        //     "avg"   => $avg
        // ];

        if ($results === null) {
            $response = 'There was a problem fetching players.';
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

        $meta = $user->leagues->where('id',$request->l_id)->first();
        $meta = $meta->pivot;

        $user = User::where('uuid',$request->uuid)->first();
                
        $results = [
            "user"      =>  $user,
            "squad"     =>  $team,
            "points"    =>  $meta->points,
            "players" => [
                "starting" => $starting,
                "subs" => $subs
            ]

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
        $league = League::where('id',$request->l_id)->first();
      
        $gk = DB::table('players')
                ->join('round_player','players.id','=','round_player.player_id')
                ->join('clubs','players.club_id','=','clubs.id')
                ->select('players.first_name','players.last_name','players.number','players.price','players.position','players.club_id',
                        'round_player.total','clubs.name')
                ->where([
                            ['round_player.round_id','=',$league->current_round],
                            ['players.position','=','GK'],
                        ])
                ->orderBy('round_player.total','desc')
                ->take(1)
                ->get();

        $def = DB::table('players')
                ->join('round_player','players.id','=','round_player.player_id')
                ->join('clubs','players.club_id','=','clubs.id')
                ->select('players.first_name','players.last_name','players.number','players.price','players.position','players.club_id',
                        'round_player.total','clubs.name')
                ->where([
                            ['round_player.round_id','=',$league->current_round],
                            ['players.position','=','DEF'],
                        ])
                ->orderBy('round_player.total','desc')
                ->take(4)
                ->get();
                
        $mid = DB::table('players')
                ->join('round_player','players.id','=','round_player.player_id')
                ->join('clubs','players.club_id','=','clubs.id')
                ->select('players.first_name','players.last_name','players.number','players.price','players.position','players.club_id',
                        'round_player.total','clubs.name')
                ->where([
                            ['round_player.round_id','=',$league->current_round],
                            ['players.position','=','MID'],
                        ])
                ->orderBy('round_player.total','desc')
                ->take(4)
                ->get();

        $atk = DB::table('players')
                ->join('round_player','players.id','=','round_player.player_id')
                ->join('clubs','players.club_id','=','clubs.id')
                ->select('players.first_name','players.last_name','players.number','players.price','players.position','players.club_id',
                        'round_player.total','clubs.name')
                ->where([
                            ['round_player.round_id','=',$league->current_round],
                            ['players.position','=','ATK'],
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

        $prev = League::where('id',$request->l_id)->first()->current_round;
        if($prev >1){
            $prev = $prev-1;
        }

        $st = DB::table('players')
                 ->join('round_player','players.id','=','round_player.player_id')
                 ->join('clubs','players.club_id','=','clubs.id')
                 ->select('clubs.name as club_name','players.first_name','players.last_name','players.id','players.number','players.position','players.price','players.club_id',
                        'round_player.assist','round_player.captain','round_player.clean','round_player.kd_3strike','k_save','round_player.miss',
                        'round_player.own_goal','round_player.player_id','round_player.red','round_player.yellow','round_player.round_id','round_player.score','round_player.start','round_player.sub','round_player.total')
                ->where('round_player.round_id','=',$prev)
                ->whereIn('players.id',$starting)
                ->get();

        $su = DB::table('players')
                ->join('round_player','players.id','=','round_player.player_id')
                ->join('clubs','players.club_id','=','clubs.id')
                ->select('clubs.name as club_name','players.first_name','players.last_name','players.id','players.number','players.position','players.price','players.club_id',
                       'round_player.assist','round_player.captain','round_player.clean','round_player.kd_3strike','k_save','round_player.miss',
                       'round_player.own_goal','round_player.player_id','round_player.red','round_player.yellow','round_player.round_id','round_player.score','round_player.start','round_player.sub','round_player.total')
               ->where('round_player.round_id','=',$prev)
               ->whereIn('players.id',$subs)
               ->get();

        // $meta = $user->oneLeague($l_id)->first();
        $meta = $user->oneLeague($request->l_id)->first()->pivot;
        $total = 0;
        foreach($st as $s){
            $total += $s->total;
        }
        
        foreach($su as $s){
            $total += $s->total;
        }
        // $meta->points = $total;
        // $meta->save();
        
        $results = [
            "selected_team" => $st,
            "substitutions" => $su,
            "total"         => $total
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
        $prev_round = League::where('id',$request->l_id)->first()->current_round;
        $prev_round--;
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

        $results = [
            "position" => $key,
            "total_players" => $no_of_users,
            "last_round"    => $previous
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

     
        // Mail::to("breddefantasy@gmail.com")->send(new SendTip($user,"$user->first_name $user->last_name has submitted a tip!",$request->body,"emails.tip_send",$league));


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
        $squad = Squad::where('league_id',$request->l_id)->where('user_id',$request->id)->first();
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
                    ['round_no','=',$prev_round]
                ])
                ->get();

        $avg = DB::table('squad_round')
                ->where([
                    ['league_id','=',$request->l_id],
                ])
                ->groupBy('squad_id')
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

    public function getPlayerInfo(Request $request)
    {
        $player = Player::where('id',$request->id)->first();
        $league = League::where('id',$player->league_id)->first()->current_round;
        $total = DB::table('players')
                ->join('round_player','players.id','=','round_player.player_id')
                ->select(DB::raw('SUM(players.price) as price'))
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

        $results = [
            "player" => $player,
            "total"  => $total,
            "current"=> $current,
            "avg"    => $avg
        ];

        if ($results === null) {
            $response = 'User does not exist';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }


    
    
}
