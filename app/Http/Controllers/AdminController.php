<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Club as Club;
use App\Match as Match;
use App\Article as Article;
use App\League as League;
use App\Round as Round;
use App\Player as Player;
use App\Season as Season;
use App\User as User;
use App\PlayerStats as PlayerStats;
use App\Squad as Squad;
use App\Tip as Tip;
use Faker\Factory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Image;


class AdminController extends Controller
{
    // *** DO NOT FORGET TO ADD RELATIONS TO QUERIES ONCE EVERYTHING IS DEFINED ***
    
    //Club CRUD section
   	public function getClubs(Request $request)
    {
        $results = Club::where('league_id',$request->l_id)->get();
        if ($results === null) {
            $response = 'There was a problem fetching your data.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

   	public function getClub(Request $request)
    {
        $results = Club::where('id', $request->id)->with('players')->first();
        if ($results === null) {
            $response = 'There was a problem fetching your data.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

    public function postClub(Request $request)
    {
        $png_url = $request->name . ".png";
        // $path = public_path() . "/images/clubs/" . $png_url;
        // $img = Image::make(file_get_contents($request->image))->save($path);

        //get the base-64 from data
        $base64_str = substr($request->image, strpos($request->image, ",")+1);
        //decode base64 string
        $image = base64_decode($base64_str);
        Storage::disk('clubs')->put($png_url,$image);

        $club = new Club;
        $club->name = $request->name;
        $club->league_id = $request->l_id;
        $club->save();

        $results = Club::where('id', $club->id)->get();
        if ($results === null) {
            $response = 'There was a problem fetching your data.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

    public function removeClub(Request $request)
    {
        $club = Club::where('id',$request->id)->first();
        $match = Match::where('id', $request->m_id)->first();
        
        if ($club === null) {
            $response = 'There was a problem fetching your data.';
            return $this->json($response, 404);
        }
        $club->delete();
        $match->delete();
        return $this->json($club);
    }

    public function updateClub(Request $request)
    {
        $club = Club::where('id',$request->id)->first();
        $club->name = $request->name;
        $club->league_id = $request->l_id;
        $club->save();

        if ($club === null) {
            $response = 'There was a problem fetching your data.';
            return $this->json($response, 404);
        }
        return $this->json($club);
    }
    

    /////////////////////////////////Club ends

    //Player CRUD section
    public function getPlayers()
    {
        $results = Player::all();
        if ($results === null) {
            $response = 'There was a problem fetching your data.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

    public function getPlayersByClub(Request $request)
    {
        $results = Club::where('league_id', $request->l_id)->with('players')->get();
      //  $results = Player::where('club_id',$request->c_id,'league_id',$request->l_id);
        if ($results === null) {
            $response = 'There was a problem fetching your data.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

    public function getPlayer(Request $request)
    {
        $results = Player::where('id', $request->id)->get();
        if ($results === null) {
            $response = 'There was a problem fetching your data.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

    public function postPlayer(Request $request)
    {
        $player = new Player;
        $player->first_name = $request->first_name;
        $player->last_name = $request->last_name;
        $player->position = $request->position;
        $player->number = $request->number;
        $player->price = $request->price;
        $player->league_id = $request->l_id;
        $player->wont_play = $request->wont_play;
        $club = Club::where('name',$request->club_name)->first();
        $player->club_id = $club->id;
        $player->reason = $request->reason;
        $player->save();

        $league = League::where('id',$club->league_id)->first();
        // $round = Round::where('round_no',$league->current_round)->where('league_id',$league->id)->first();
        $rounds = Round::where('league_id',$league->id)->where('round_no','>=',$league->current_round)->get();
        foreach($rounds as $round){
            $round->players()->attach($player);
        }

        $results = Player::where('id', $player->id)->get();
        if ($results === null) {
            $response = 'There was a problem fetching your data.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

    public function removePlayer(Request $request)
    {
        $player = Player::where('id',$request->id)->first();
        $round_players = $player->rounds()->where('player_id',$player->id)->get();
   
        foreach($round_players as $round_player){
            $round_player->pivot->delete();
        }
        // $club = Club::where('id',$player->club_id)->first();
        // $league = League::where('id',$club->league_id)->first();
        // $round = Round::where('league_id',$league->id)->where()
        
        if ($player === null) {
            $response = 'There was a problem fetching your data.';
            return $this->json($response, 404);
        }
        $player->delete();
        return $this->json($player);
    }

    public function updatePlayer(Request $request)
    {
        $player = Player::where('id',$request->id)->first();
        $player->first_name = $request->first_name;
        $player->last_name = $request->last_name;
        $player->position = $request->position;
        $player->number = $request->number;
        $player->price = $request->price;
        $player->league_id = $request->l_id;
        $player->wont_play = $request->wont_play;        
        $club=Club::where('name',$request->club_name)->first();
        $player->club_id = $club->id;
        $player->reason = $request->reason;        
        $player->save();

        if ($player === null) {
            $response = 'There was a problem fetching your data.';
            return $this->json($response, 404);
        }
        return $this->json($player);
    }

    /////////////////////////////////Club ends

    //League CRUD

   	public function getLeagues()
    {
        $results = League::all();
        if ($results === null) {
            $response = 'There was a problem fetching your data.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

   	public function getLeague(Request $request)
    {
        $results = League::where('id', $request->id)->get();
        if ($results === null) {
            $response = 'There was a problem fetching your data.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

    public function postLeague(Request $request)
    {
    	$league = new League();
    	$league->name = $request->name;
    	$league->number_of_rounds = $request->number_of_rounds;
        $league->save();
        
        for($i=0;$i<$league->number_of_rounds;$i++){
            $round = new Round;
            $round->league_id = $league->id;
            $round->round_no = $i+1;
            $round->save();
        }

        $results = League::where('id', $league->id)->get();
        if ($results === null) {
            $response = 'There was a problem saving your data.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

    public function removeLeague(Request $request)
    {
        $league = League::where('id',$request->id)->first();
        
        if ($league === null) {
            $response = 'There was a problem fetching your data.';
            return $this->json($response, 404);
        }
        $league->delete();
        return $this->json($league);
    }

    public function updateLeague(Request $request)
    {   
        $new_r = $request->number_of_rounds;
        
        $league = League::where('id',$request->id)->first();
        $old_r = $league->number_of_rounds;
        if($old_r < $new_r)
        {
            $r = $new_r - $old_r;
            for($i=$old_r; $i<$new_r; $i++)
            {
                $round = new Round;
                $round->league_id = $league->id;
                $round->round_no = $i+1;
                $round->save();
            }
        }elseif($old_r > $new_r){

            for($i=$old_r;$i>$new_r;$i--)
            {
                $round = Round::where('round_no',$i)->where('league_id',$league->id)->first();
                $round->delete();
            }
        }

        $league->name = $request->name;
        $league->number_of_rounds = $request->number_of_rounds;
        $league->save();

        if ($league === null) {
            $response = 'There was a problem fetching your data.';
            return $this->json($response, 404);
        }
        return $this->json($league);
    }

    /////////////////////////////////League ends

    //Match CRUD

  	public function getMatches(Request $request)
    {
        $results = Match::where('round_id',$request->r_id)->where('league_id',$request->l_id)->get();
        if ($results === null) {
            $response = 'There was a problem fetching your data.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

    public function getMatchesByRounds(Request $request)
    {
      //  $results = League::where('id', $request->l_id)->with('rounds.matches')->get();
      $results = Round::where('league_id',$request->l_id)->with('matches')->get();
     
      
        if ($results === null) {
            $response = 'There was a problem fetching your data.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

   	public function getMatch(Request $request)
    {
        $results = Match::where('id', $request->id)->first();
        if ($results === null) {
            $response = 'There was a problem fetching your data.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

   	public function postMatch(Request $request)
    {
    	$match = new Match();
    	$match->club1_name = $request->c1_name;
        $match->club2_name = $request->c2_name;
        $match->time = $request->time;
        $round = Round::where('league_id',$request->l_id)->where('round_no',$request->r_no)->first();
        //return $round;
        $match->round_id = $round->id;
        $match->league_id = $request->l_id;
        $match->club1_score = $request->c1_score;
        $match->club2_score = $request->c2_score;
        // important order
        $matches = $round->matches()->get();
    	$match->save();
        

        $exists1 = 0;
        $exists2 = 0;
        if($matches!==null){    
            if(isset($round->matches()->first()->time)){
                $end_time = $round->matches()->first()->time;
            
            foreach($matches as $m){
                if($m->time > $end_time){
                    $end_time = $m->time;
                }
                if(($m->club1_name === $request->c1_name) or ($m->club2_name === $request->c1_name)){
                    $exists1 = 1;
                }
                if(($m->club1_name === $request->c2_name) or ($m->club2_name === $request->c2_name)){
                    $exists2 = 1;
                }
            }
            $round->end_time = $end_time;
            $round->save();
         }
        }

        if($exists1===0){
            $club1 = Club::where('name',$request->c1_name)->first();
            $players1 = $club1->players;
            foreach($players1 as $player){
                $round->players()->attach($player);            
            }
        }

        if($exists2===0){
            $club2 = Club::where('name',$request->c2_name)->first();
            $players2 = $club2->players;
            foreach($players2 as $player){
                  $round->players()->attach($player);      
            }    
        }

        if ($match === null) {
            $response = 'There was a problem fetching your data.';
            return $this->json($response, 404);
        }
        return $this->json($match);
    }

    public function removeMatch(Request $request)
    {
        $match = Match::where('id',$request->id)->first();
        $round = Round::where('league_id',$request->l_id)->where('round_no',$request->r_no)->first();
        
        $count1 = 0;
        $count2 = 0;
        $matches = $round->matches()->get();

        $time = 0;
        $end_time = $round->end_time;
        if($match->time >= $end_time){
            $time = 1;
        }

        foreach($matches as $m){
            if($match->club1_name == $m->club1_name || $match->club2_name == $m->club1_name){
                $count1++;
            }
            if($match->club1_name == $m->club2_name || $match->club2_name == $m->club2_name){
                $count2++;
            }

        }
        
        if($count1<2){
            $club1 = Club::where('name',$match->club1_name)->first();
            $players1 = $club1->players;
            foreach($players1 as $player){
                $round->players()->detach($player);     
            }
        }

        if($count2<2){
             $club2 = Club::where('name',$match->club2_name)->first();
             $players2 = $club2->players;
             foreach($players2 as $player){
                 $round->players()->detach($player);   
             }
        }

        if ($match === null) {
            $response = 'There was a problem fetching your data.';
            return $this->json($response, 404);
        }
        $match->delete();
        $matches = $round->matches()->get();
        if($time == 1 ){
            if(isset($round->matches()->first()->time)){
                $end_time = $round->matches()->first()->time;
            }
            foreach($matches as $m){
                if($m->time > $end_time){
                    $end_time = $m->time;
                }
            }
            $round->end_time = $end_time;
            $round->save();
        }

        return $this->json($match);
    }

    public function updateMatch(Request $request)
    {
        // $new_round = Round::where('round_no',$request->r_no)->where('league_id',$request->l_id)->first();
        // return $round->id;
        $match = Match::where('id',$request->id)->first();
        $round = $match->round;

        // if($current_round->round_no != $request->r_no)
        // {
        //     $match->delete();

        //     $newMatch = new Match;
        //     $newMatch->club1_name = $request->c1_name;
        //     $newMatch->club2_name = $request->c2_name;
        //     $newMatch->time = $request->time;   
        //     $newMatch->round_id = $new_round->id;
        //     $newMatch->league_id = $request->l_id;
        //     $newMatch->save();
        //     return $this->json($newMatch);
        // }     
        $match->club1_name = $request->c1_name;
        $match->club2_name = $request->c2_name;
        $match->club1_score = $request->c1_score;
        $match->club2_score = $request->c2_score;

        $match->time = $request->time;
        $match->save();

        $matches = $round->matches()->get();
/////////////////

        $club1 = Club::where('name',$match->club1_name)->first();
        $players1 = $club1->players;
        foreach($players1 as $player){
            if($round->players()->where('player_id',$player->id)->exists()){
                break;
            }else{
              $round->players()->attach($player);              
            }
        }

        $club2 = Club::where('name',$match->club2_name)->first();
        $players2 = $club2->players;
        foreach($players2 as $player){
            if($round->players()->where('player_id',$player->id)->exists()){
                break;
            }else{
              $round->players()->attach($player);              
            }
        }

        $end_time = $round->matches()->first()->time;
        foreach($matches as $match){
            if($match->time > $end_time){
                $end_time = $match->time;
            }
        }
        $round->end_time = $end_time;
        $round->save();
        $match = Match::where('id',$request->id)->first();
        
        if ($match === null) {
            $response = 'There was a problem fetching your data.';
            return $this->json($response, 404);
        }
        return $this->json($match);
    }

    /////////////////////////////////Match ends

    //Round CRUD

   	public function getRounds(Request $request)
    {
        $results = Round::where('league_id',$request->l_id)->get();
        if ($results === null) {
            $response = 'There was a problem fetching your data.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

   	public function getRound(Request $request)
    {
        $results = Round::where('id', $request->id)->get();
        if ($results === null) {
            $response = 'There was a problem fetching your data.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

    public function postRound(Request $request)
    {
        $max = League::where('id',$request->l_id)->first()->number_of_rounds;
        if($request->round_no <= $max)
        {
            $round = new Round();
            $round->round_no = $request->round_no;
            $round->league_id = $request->l_id;
            $round->save();
        }else{
            $response = 'Maximum number of rounds.';
            return $this->json($response, 404);
        }

        $results = Round::where('id', $round->id)->get();
        if ($results === null) {
            $response = 'There was a problem saving your data.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

    // public function removeRound(Request $request)
    // {
    //     $round = Round::where('id',$request->id)->first();
        
    //     if ($round === null) {
    //         $response = 'There was a problem fetching your data.';
    //         return $this->json($response, 404);
    //     }
    //     $round->delete();
    //     return $this->json($round);
    // }

    public function updateRound(Request $request)
    {
        $round->round_no = $request->round_no;
        $round->league_id = $request->l_id;
    	$round->save();

        $round = Round::where('id', $round->id)->get();
        if ($round === null) {
            $response = 'There was a problem saving your data.';
            return $this->json($response, 404);
        }
        return $this->json($round);
    }

    public function setDeadline(Request $request)
    {
        $round = Round::where('round_no', $request->r_no)->where('league_id', $request->l_id)->first();
        $round->deadline = $request->time;
        $round->save();

        if ($round === null) {
            $response = 'There was a problem updating your data.';
            return $this->json($response, 404);
        }
        return $this->json($round);
    }

    /////////////////////////////////Round ends

    //Season CRUD

   	public function getSeasons()
    {
        $results = Season::all();
        if ($results === null) {
            $response = 'There was a problem fetching your data.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

   	public function getSeason(Request $request)
    {
        $results = Season::where('id', $request->id)->get();
        if ($results === null) {
            $response = 'There was a problem fetching your data.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

    public function postSeason(Request $request)
    {
        $season = new Season();
        $season->name = $request->name;
    	$season->save();

        $results = Season::where('id', $season->id)->get();
        if ($results === null) {
            $response = 'There was a problem saving your data.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

    public function removeSeason(Request $request)
    {
        $season = Season::where('id',$request->id)->first();
        
        if ($season === null) {
            $response = 'There was a problem fetching your data.';
            return $this->json($response, 404);
        }
        $season->delete();
        return $this->json($season);
    }

    public function updateSeason(Request $request)
    {
        $season = Season::where('id',$request->id)->first();
        $season->name = $request->name;
    	$season->save();

        $season = Season::where('id', $season->id)->get();
        if ($season === null) {
            $response = 'There was a problem saving your data.';
            return $this->json($response, 404);
        }
        return $this->json($season);
    }

    /////////////////////////////////Season ends

    //User CRUD

   	public function getUsers(Request $request)
    {
        // $results = User::all();
        $results = DB::table('users')
                    ->join('user_league','users.id','=','user_league.user_id')
                    ->select('users.*')
                    ->where('user_league.league_id','=',$request->l_id)
                    ->get();
            
        if ($results === null) {
            $response = 'There was a problem fetching your data.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

   	public function getUser(Request $request)
    {
        $results = User::where('id', $request->id)->get();
        if ($results === null) {
            $response = 'There was a problem fetching your data.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

    public function postUser(Request $request)
    {
        $user = new User;
        $user->username = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        // $user->password = bcrypt($request->password);
        $user->uuid = Factory::create()->uuid;
        $user->first_name = "created by admin";
        $user->last_name = "created by admin";
        $user->save();
        $squad = new Squad;
        $squad->user_id = $user->id;
        $squad->league_id = $request->l_id;
        $squad->save();
        $league = League::where('id',$request->l_id)->first();
        // $user->leagues()->attach($request->l_id,$user);
        $league->users()->attach($user,['money' => 100000 ,'points' => 0,'league_id'=>$request->l_id ,'squad_id'=> $squad->id]);
        $user->roles()->attach($user,['role_id'=>1]);

        $results = User::where('id', $user->id)->get();
        if ($results === null) {
            $response = 'There was a problem saving your data.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

    public function removeUser(Request $request)
    {
        $user = User::where('uuid',$request->uuid)->first();
        $squad = Squad::where('user_id',$user->id)->first();
        
        if ($user === null) {
            $response = 'There was a problem fetching your data.';
            return $this->json($response, 404);
        }
        $user->delete();
        return $this->json($user);
    }

    public function updateUser(Request $request)
    {
        $user = User::where('uuid',$request->id)->first();
        $user->name = $request->name;
        $user->email = $request->email;

    	$user->save();

        $user = User::where('id', $user->id)->get();
        if ($user === null) {
            $response = 'There was a problem saving your data.';
            return $this->json($response, 404);
        }
        return $this->json($user);
    }

    /////////////////////////////////User ends

    //News CRUD

  	public function getArticles()
      {
          $results = Article::all();
          if ($results === null) {
              $response = 'There was a problem fetching your data.';
              return $this->json($response, 404);
          }
          return $this->json($results);
      }
  
    public function getArticle(Request $request)
    {
        $results = Article::where('id', $request->id)->get();
        if ($results === null) {
            $response = 'There was a problem fetching your data.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }
  
    public function postArticle(Request $request)
    {
        $png_url = "news-".time().".png";
        // $path = public_path() . "/images/news/" . $png_url;
        // $img = Image::make(file_get_contents($request->image))->save($path);

        //get the base-64 from data
        $base64_str = substr($request->image, strpos($request->image, ",")+1);
        //decode base64 string
        $image = base64_decode($base64_str);
        Storage::disk('news')->put($png_url,$image);

        $article = new Article();
        $article->title = $request->title;
        $article->body = $request->body;
        $article->league_id = $request->l_id;
        $article->image_path = $png_url;
        $article->slug = strtolower(str_replace(' ','-',$article->title));
        $article->save();

        if ($article === null) {
            $response = 'There was a problem fetching your data.';
            return $this->json($response, 404);
        }
        return $this->json($article);
    }
  
    public function removeArticle(Request $request)
    {
        $article = Article::where('id',$request->id)->first();
        
        if ($article === null) {
            $response = 'There was a problem fetching your data.';
            return $this->json($response, 404);
        }
        $article->delete();
        return $this->json($article);
    }

    public function updateArticle(Request $request)
    {
        $article = Article::where('id',$request->id)->first();
        $article->title = $request->title;
        $article->body = $request->body;
        $article->league_id = $request->l_id;
        $oldpath = $article->image;
        $article->save();

        if($article->image_path !== null && $article->image_path)
        
        

        if ($article === null) {
            $response = 'There was a problem fetching your data.';
            return $this->json($response, 404);
        }
        return $this->json($article);
    }
  
    /////////////////////////////////News ends

    //Tips CRUD

  	public function getTips(Request $request)
      {
          $results = Tip::where('league_id',$request->l_id)->get();
          if ($results === null) {
              $response = 'There was a problem fetching your data.';
              return $this->json($response, 404);
          }
          return $this->json($results);
      }
  
    public function getTip(Request $request)
    {
        $results = Tip::where('id', $request->id)->get();
        if ($results === null) {
            $response = 'There was a problem fetching your data.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }
  
    public function removeTip(Request $request)
    {
        $tip = Tip::where('id',$request->id)->first();
        
        if ($tip === null) {
            $response = 'There was a problem fetching your data.';
            return $this->json($response, 404);
        }
        $tip->delete();
        return $this->json($tip);
    }

    /////////////////////////////////Tips ends

    //Admin accounts CRUD

    public function getAdmins(Request $request)
    {
        // $results = User::all();
        $results = DB::table('users')
                    ->join('user_league','users.id','=','user_league.user_id')
                    ->select('users.*')
                    ->where('user_league.league_id','=',$request->l_id)
                    ->get();
            
        if ($results === null) {
            $response = 'There was a problem fetching your data.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

   	public function getAdmin(Request $request)
    {
        $results = User::where('id', $request->id)->get();
        if ($results === null) {
            $response = 'There was a problem fetching your data.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

    public function postAdmin(Request $request)
    {
        $user = new User;
        $user->username = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        // $user->password = bcrypt($request->password);
        $user->uuid = Factory::create()->uuid;
        $user->first_name = "created by admin";
        $user->last_name = "created by admin";
        $user->save();
        $squad = new Squad;
        $squad->user_id = $user->id;
        $squad->league_id = $request->l_id;
        $squad->save();
        $league = League::where('id',$request->l_id)->first();
        // $user->leagues()->attach($request->l_id,$user);
        $league->users()->attach($user,['money' => 100000 ,'points' => 0,'league_id'=>$request->l_id ,'squad_id'=> $squad->id]);
        $user->roles()->attach($user,['role_id'=>1]);

        $results = User::where('id', $user->id)->get();
        if ($results === null) {
            $response = 'There was a problem saving your data.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

    public function removeAdmin(Request $request)
    {
        $user = User::where('uuid',$request->uuid)->first();
        $squad = Squad::where('user_id',$user->id)->first();
        
        if ($user === null) {
            $response = 'There was a problem fetching your data.';
            return $this->json($response, 404);
        }
        $user->delete();
        return $this->json($user);
    }

    public function updateAdmin(Request $request)
    {
        $user = User::where('uuid',$request->id)->first();
        $user->name = $request->name;
        $user->email = $request->email;

    	$user->save();

        $user = User::where('id', $user->id)->get();
        if ($user === null) {
            $response = 'There was a problem saving your data.';
            return $this->json($response, 404);
        }
        return $this->json($user);
    }



    /////////////////////////////////Admin accounts ends
    

    //Post match stats

    public function getClubsByMatch(Request $request)
    {
        $match = Match::where('id', $request->m_id)->first();
        $club1 = Club::where('league_id',$request->l_id)->where('name', $match->club1_name)->first();
        $club2 = Club::where('league_id',$request->l_id)->where('name', $match->club2_name)->first();

        $round = Round::where('round_no',$request->r_id)->first();
        
        $players = $round->players;

        $club1 = $club1->players()->with('rounds')->get();
        $club2 = $club2->players()->with('rounds')->get();
        
        // return $club1->players;
        
        $i = 0;
        foreach($players as $player){
            $stats[$i] = $player->pivot;
            $i++;
        }

        
        $results = [
            "club1" => $club1,
            "club2" => $club2
        ];

        if ($results === null) {
            $response = 'There was a problem fetching your data.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

    public function getPlayersStats(Request $request)
    {  
        // $round = Round::where('round_no',$request->r_no)->where('league_id',$request->l_id)->first();
        
        // return $round->matches->where('round_id',$request->r_no);
        // $players = $round->players;
        
        // $i = 0;
        // foreach($players as $player){
        //     $results[$i] = $player->pivot;
        //     $i++;
        // }

        ////////////////////////////////

        $match = Match::where('id', $request->m_id)->first();
        $club1 = Club::where('league_id',$request->l_id)->where('name', $match->club1_name)->first();
        $club2 = Club::where('league_id',$request->l_id)->where('name', $match->club2_name)->first();


        $c1 = DB::table('players')
                    ->join('round_player','players.id','=','round_player.player_id')
                    ->select('players.first_name','players.last_name','players.id','players.number','players.position','players.price','players.club_id',
                            'round_player.assist','round_player.captain','round_player.clean','round_player.kd_3strike','k_save','round_player.miss',
                            'round_player.own_goal','round_player.player_id','round_player.red','round_player.yellow','round_player.round_id','round_player.score','round_player.start','round_player.sub','round_player.total')
                    ->where([
                                // ['round_player.round_id','=',$request->r_id],
                                ['round_player.round_id','=',$match->round_id],
                                ['players.club_id', '=', $club1->id],
                            ])
                    ->get();

        $c2 = DB::table('players')
                    ->join('round_player','players.id','=','round_player.player_id')
                    ->select('players.first_name','players.last_name','players.id','players.number','players.position','players.price','players.club_id',
                            'round_player.assist','round_player.captain','round_player.clean','round_player.kd_3strike','k_save','round_player.miss',
                            'round_player.own_goal','round_player.player_id','round_player.red','round_player.yellow','round_player.round_id','round_player.score','round_player.start','round_player.sub','round_player.total')
                    ->where([
                                // ['round_player.round_id','=',$request->r_id],
                                ['round_player.round_id','=',$match->round_id],
                                ['players.club_id', '=', $club2->id],
                            ])
                    ->get();

        $results = [
            "club1" => $c1,
            "club2" => $c2,
        ];
                    
        // $c1 = DB::table('players')
        //             ->join('round_player', function($join){
        //                 $join->on('round_player','players.id','=','round_player.player_id')
        //                         ->select('players.first_name','players.last_name','players.id','players.number','players.position','players.price','players.club_id',
        //                         'round_player.assist','round_player.captain','round_player.clean','round_player.kd_3strike','k_save','round_player.miss',
        //                         'round_player.own_goal','round_player.player_id','round_player.red','round_player.yellow','round_player.round_id','round_player.score','round_player.start','round_player.sub')
        //                         ->where('players.club_id', '=', $club1->id);
        //             })
        //             ->get();



        if ($results === null) {
            $response = 'There was a problem fetching your data.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

    public function postPlayerStats(Request $request)
    {
        $round = Round::where('round_no',$request->input('data.round_id'))->where('league_id',$request->l_id)->first();
        $player = $round->players->where('pivot.player_id',$request->input('data.player_id'))->where('pivot.round_id',$round->round_no)->first()->pivot;
        $position = Player::where('id',$request->input('data.player_id'))->first()->position;
        $stats = [
            "start"     =>  $player->start = $request->input('data.start'),
            "sub"       =>  $player->sub = $request->input('data.sub'),
            "assist"    =>  $player->assist = $request->input('data.assist'),
            "miss"      =>  $player->miss = $request->input('data.miss'),
            "score"     =>  $player->score = $request->input('data.score'),
            "clean"     =>  $player->clean = $request->input('data.clean'),
            "k_save"    =>  $player->k_save = $request->input('data.k_save'),
            "kd_3strike"=>  $player->kd_3strike = $request->input('data.kd_3strike'),
            "yellow"    =>  $player->yellow = $request->input('data.yellow'),
            "red"       =>  $player->red = $request->input('data.red'),
            "own_goal"  =>  $player->own_goal = $request->input('data.own_goal'),
            "captain"   =>  $player->captain = $request->input('data.captain'),
            "position"  =>  $position
        ];
        $player->save();
        $player->total = $this->playerTotalPoints($stats);
        $player->save();

        if ($player === null) {
            $response = 'There was a problem fetching your data.';
            return $this->json($response, 404);
        }
        return $this->json($player);
    }

    public function playerTotalPoints($stats)
    {
        //$player = Player::where('id',$id)->first(); 
 
        $total = 0;
        
        switch($stats["position"]):
            case 'ATK':
                $total += $stats["start"]*2;
                $total += $stats["sub"]*1;
                $total += $stats["score"]*3;
                $total += $stats["assist"]*3;
                // ?
                // $total += $stats["clean"]*0;
                // $total += $stats["k_save"]*0;
                // $total += $stats["kd_3strike"]*0;
                //
                $total += $stats["miss"]*(-2);
                $total += $stats["yellow"]*(-1);
                $total += $stats["red"]*(-3);
                $total += $stats["own_goal"]*(-2);
                $total += $stats["captain"]*2;

                break;
            case "MID":
                $total += $stats["start"]*2;
                $total += $stats["sub"]*1;
                $total += $stats["score"]*4;
                $total += $stats["assist"]*3;
                // ?
                $total += $stats["clean"]*2;
                // $total += $stats["k_save"]*0;
                // $total += $stats["kd_3strike"]*0;
                //
                $total += $stats["miss"]*(-2);
                $total += $stats["yellow"]*(-1);
                $total += $stats["red"]*(-3);
                $total += $stats["own_goal"]*(-2);
                $total += $stats["captain"]*2;
               
                break;    
            case 'DEF':
                $total += $stats["start"]*2;
                $total += $stats["sub"]*1;
                $total += $stats["score"]*6;
                $total += $stats["assist"]*3;
                // ?
                $total += $stats["clean"]*5;
                // $total += $stats["k_save"]*0;
                $total += $stats["kd_3strike"]*(-1);
                //
                $total += $stats["miss"]*(-2);
                $total += $stats["yellow"]*(-1);
                $total += $stats["red"]*(-3);
                $total += $stats["own_goal"]*(-2);
                $total += $stats["captain"]*2;
               
                break;                            
            case "GK":
                $total += $stats["start"]*2;
                $total += $stats["sub"]*1;
                $total += $stats["score"]*8;
                $total += $stats["assist"]*3;
                // ?
                $total += $stats["clean"]*5;
                $total += $stats["k_save"]*5;
                $total += $stats["kd_3strike"]*(-1);
                //
                $total += $stats["miss"]*(-2);
                $total += $stats["yellow"]*(-1);
                $total += $stats["red"]*(-3);
                $total += $stats["own_goal"]*(-2);
                $total += $stats["captain"]*2;
               
                break;
        endswitch;
        return $total;

    }

    /////////////////////////////////Post match stats ends

    public function evaluateUserPoints(Request $request)
    {
            $users = User::all();
            $prev = League::where('id',$request->l_id)->first()->current_round;
            // if($prev >1){
            //     $prev = $prev-1;
            // }
            foreach($users as $user){
                $team = Squad::where('user_id',$user->id)->where('league_id',$request->l_id)->first();

                // return $team->with('rounds')->where()->get();
                $cpt = $team->captain_id;
                if($team->selected_team === NULL || $team->substitutions === NULL){
                    continue;
                }else{
                    $starting = json_decode($team->selected_team);
                    $subs = json_decode($team->substitutions);
                    $starting_arr = array_values(json_decode(json_encode($starting), true));
                    $subs_arr = array_values(json_decode(json_encode($subs), true));

                    $start_ = implode(',', $starting_arr);
                    $st = DB::table('players')
                            ->join('round_player','players.id','=','round_player.player_id')
                            ->join('clubs','players.club_id','=','clubs.id')
                            ->select('clubs.name as club_name','players.first_name','players.last_name','players.id','players.number','players.position','players.price','players.club_id',
                                    'round_player.assist','round_player.captain','round_player.clean','round_player.kd_3strike','k_save','round_player.miss',
                                    'round_player.own_goal','round_player.player_id','round_player.red','round_player.yellow','round_player.round_id','round_player.score','round_player.start','round_player.sub','round_player.total')
                            ->where('round_player.round_id','=',$prev)
                            ->whereIn('players.id',$starting_arr)
                            ->orderByRaw(DB::raw("FIELD(players.id,$start_)"))
                            ->get();
                    
                    $subs_ = implode(',', $subs_arr);
                    $su = DB::table('players')
                            ->join('round_player','players.id','=','round_player.player_id')
                            ->join('clubs','players.club_id','=','clubs.id')
                            ->select('clubs.name as club_name','players.first_name','players.last_name','players.id','players.number','players.position','players.price','players.club_id',
                                'round_player.assist','round_player.captain','round_player.clean','round_player.kd_3strike','k_save','round_player.miss',
                                'round_player.own_goal','round_player.player_id','round_player.red','round_player.yellow','round_player.round_id','round_player.score','round_player.start','round_player.sub','round_player.total')
                            ->where('round_player.round_id','=',$prev)
                            ->whereIn('players.id',$subs_arr)
                            ->orderByRaw(DB::raw("FIELD(players.id,$subs_)"))
                            ->get();
                   
                 
            
                    // $meta = $user->oneLeague($l_id)->first();
                    $meta = $user->oneLeague($request->l_id)->first();
                    if($meta==null){
                        continue;
                    }else{
                        $meta = $meta->pivot;
                        $prev_total = $meta->points;
                        $total = 0;
                        $started = 0;
                        $gk = 0; 
                        foreach($st as $s){
                            if(($s->start==1 || $s->sub==1) && $s->position!="GK"){
                                if($s->id===$cpt){
                                    $total += ($s->total)*2;
                                }else{
                                    $total += $s->total;
                                }
                                $started++;
                            }elseif(($s->start==1 || $s->sub==1) && $s->position==="GK"){
                                if($s->id===$cpt){
                                    $total += ($s->total)*2;
                                }else{
                                    $total += $s->total;
                                }
                                $gk = 1;
                            }
                            
                        }
                        
                        $left = 10-$started;
                        if($left>3){
                            $left=3;
                        }
                        if($gk==0){
                            if($su[0]->id===$cpt){
                                $total += ($su[0]->total)*2;
                            }else{
                                $total += $su[0]->total;
                            }
                        }
                        
                        if($left>0){
                            for($i=1;$i<=$left;$i++){
                                if($su[$i]->id===$cpt){
                                    $total += ($su[$i]->total)*2;
                                }else{
                                    $total += $su[$i]->total;
                                }
                            }
                        }
        

                        // foreach($su as $s){
                        //     $total += $s->total;
                        // }
                        $round = Round::where('round_no',$prev)->where('league_id',$request->l_id)->first();

                        $q = $team->rounds()->where('round_id',$round->id)->first();     
                        if(empty($q)){
                            $team->rounds()->attach($team,['round_id' => $round->id, 'points' => $total,'league_id' => $request->l_id,'round_no' => $prev , "squad_id" => $team->id]);
                            // return "prazno";
                        }else{
                            $prev_total -= $q->pivot->points;
                            $team->rounds()->updateExistingPivot($round->id,['round_id' => $round->id, 'points' => $total,'league_id' => $request->l_id,'round_no' => $prev , "squad_id" => $team->id]);
                            // return "neprazno";
                        }

                        $total += $prev_total;

                        $meta->points = $total;
                        $meta->save();
                    }
                }
                }
                    $response = "success";
                    return $this->json($response);
            
                
            
            
        
    }

    public function prevRound(Request $request)
    {
        $users = User::all();
        $league = League::where('id',$request->l_id)->first();
        if($league->current_round > 1){
            $league->current_round--;
            $league->save();
            $users = User::all();
            foreach($users as $user){
                $meta = $user->oneLeague($request->l_id)->first()->pivot;
                $meta->transfers = 2;
                $meta->save();
            }
        }else{
            $response = "There is no previous round.";
            return $this->json($response,404);
        }
        return $this->json($league->current_round);
    }

    public function nextRound(Request $request)
    {
       
        $league = League::where('id',$request->l_id)->first();
        if($league->current_round < $league->number_of_rounds){
            $league->current_round++;
            $league->save();
            $users = User::all();
            foreach($users as $user){
                $meta = $user->oneLeague($request->l_id)->first()->pivot;
                $meta->transfers = 2;
                $meta->save();
            }
        }else{
            $response = "There is no next round.";
            return $this->json($response,404);
        }
        return $this->json($league->current_round);
    }

}
