<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Article as Article;
use App\User as User;
use App\League as League;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\File;
use App\Squad as Squad;
use App\Player as Player;
use Image;


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

    public function getAllLeagues()
    {
        $results = League::all();
        if ($results === null) {
            $response = 'There was a problem fetching players.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

    public function getAllNews()
    {
        $results = Article::all();
        if ($results === null) {
            $response = 'There was a problem fetching players.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

    public function getNewsByLeague(Request $request)
    {
        $results = Article::where('league_id', $request->l_id)->get();
        if ($results === null) {
            $response = 'There was a problem fetching players.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

    public function getLatestNews(Request $request)
    {
        $results = Article::where('league_id',$request->l_id)->orderBy('created_at', 'desc')->first();
 
        if ($results === null) {
            $response = 'There was a problem fetching players.';
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
        $results = User::with('leagues')->where('id', $request->id)->take(5)->get();
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

        $response = Response::make(Storage::disk('public')->get($request->name));
        $response->header('Content-Type', 'image/png');
        return $response;

        if ($results === null) {
            $response = 'There was a problem fetching players.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

    // with i argument???
    public function getUsers(Request $request)
    {
        // $results = User::with(['oneLeague' => $request->l_id])->get();
        // $users = User::all();
        // $results = $users->oneLeague($request->l_id)->get();
     
        $results = DB::table('users')
                ->join('user_league','users.id','=','user_league.user_id')
                ->select('users.first_name','users.last_name','users.uuid','users.created_at','users.username',
                        'user_league.money','user_league.points','user_league.squad_id')
                ->where('user_league.league_id','=',$request->l_id)
                ->orderBy('user_league.points','desc')
                ->take(10)
                ->get();

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
                ->select('players.first_name','players.last_name','players.number','players.price','players.position','players.club_id',
                        'round_player.total')
                ->where([
                            ['round_player.round_id','=',$league->current_round],
                            ['players.position','=','GK'],
                        ])
                ->orderBy('round_player.total','desc')
                ->take(1)
                ->get();

        $def = DB::table('players')
                ->join('round_player','players.id','=','round_player.player_id')
                ->select('players.first_name','players.last_name','players.number','players.price','players.position','players.club_id',
                        'round_player.total')
                ->where([
                            ['round_player.round_id','=',$league->current_round],
                            ['players.position','=','DEF'],
                        ])
                ->orderBy('round_player.total','desc')
                ->take(4)
                ->get();
                
        $mid = DB::table('players')
                ->join('round_player','players.id','=','round_player.player_id')
                ->select('players.first_name','players.last_name','players.number','players.price','players.position','players.club_id',
                        'round_player.total')
                ->where([
                            ['round_player.round_id','=',$league->current_round],
                            ['players.position','=','MID'],
                        ])
                ->orderBy('round_player.total','desc')
                ->take(4)
                ->get();

        $atk = DB::table('players')
                ->join('round_player','players.id','=','round_player.player_id')
                ->select('players.first_name','players.last_name','players.number','players.price','players.position','players.club_id',
                        'round_player.total')
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
        
    }
    
}
