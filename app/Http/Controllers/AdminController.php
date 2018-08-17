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
use App\Newsletter as Newsletter;
use Faker\Factory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Mail as Mail;
use App\Mail\RegistrationMail;
use App\Mail\NewsletterMail;
// use Image;
use Carbon\Carbon;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\URL;


/**
 * @resource Admin
 *
 * Admin control panel routes, admin authentication required for all routes
 */
class AdminController extends Controller
{
    public $club_name;

    //Club CRUD section

    /**
     * Get clubs
     *
     * Getter for all clubs in selected league // params: l_id (league id)
     * 
     */ 
   	public function getClubs(Request $request)
    {
        $results = Club::where('league_id',$request->l_id)->get();
        if ($results === null) {
            $response = 'There was a problem fetching your data.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

    /**
     * Get club
     *
     * Getter for a specific club// params: id (club id)
     * 
     */ 
   	public function getClub(Request $request)
    {
        $results = Club::where('id', $request->id)->with('players')->first();
        if ($results === null) {
            $response = 'There was a problem fetching your data.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

    /**
     * Create club
     *
     * Create a new club// params: l_id (league id),name, image (base64 image), 
     * 
     */ 
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

    /**
     * Remove a club
     *
     * Remove an existing club // params: l_id (league id),name, 
     * 
     */ 
    public function removeClub(Request $request)
    {
        $club = Club::where('id',$request->id)->first();
        // $match = Match::where('id', $request->m_id)->first();
        
        if ($club === null) {
            $response = 'There was a problem fetching your data.';
            return $this->json($response, 404);
        }
        $club->delete();
        // $match->delete();
        return $this->json($club);
    }

    /**
     * Update club
     *
     * Update an existing club// params: l_id (league id),id (club id), name 
     * 
     */ 
    public function updateClub(Request $request)
    {
        $club = Club::where('id',$request->id)->first();
        $club->name = $request->name;
        $club->league_id = $request->l_id;
        $club->save();

        $png_url = $club->name . ".png";
        // $path = public_path() . "/images/clubs/" . $png_url;
        // $img = Image::make(file_get_contents($request->image))->save($path);

        //get the base-64 from data
        $base64_str = substr($request->image, strpos($request->image, ",")+1);
        //decode base64 string
        $image = base64_decode($base64_str);
        Storage::disk('clubs')->put($png_url,$image);

        if ($club === null) {
            $response = 'There was a problem fetching your data.';
            return $this->json($response, 404);
        }
        return $this->json($club);
    }
    

    /////////////////////////////////Club ends

    //Player CRUD section

    /**
     * Get players
     *
     * Getter for all players in selected league // params: l_id (league id)
     * 
     */ 
    public function getPlayers()
    {
        $results = Player::all();
        if ($results === null) {
            $response = 'There was a problem fetching your data.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

    /**
     * Get players by club
     *
     * Getter for all players by clubs in selected league // params: l_id (league id)
     * 
     */
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

    /**
     * Get player
     *
     * Getter for a specific player// params: id (player id)
     * 
     */
    public function getPlayer(Request $request)
    {
        $results = Player::where('id', $request->id)->get();
        if ($results === null) {
            $response = 'There was a problem fetching your data.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

    /**
     * Create player
     *
     * Create a new player// params: l_id (league id),first_name, last_name, position(in:GK,DEF,MID,ATK), number, price(integer in NOK), club_name, wont_play (integer,% of not playing next round), reason(string, reason of not playing)
     * 
     */ 
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
        $this->club_name = $club->name;


        $league = League::where('id',$club->league_id)->first();
        // $round = Round::where('round_no',$league->current_round)->where('league_id',$league->id)->first();
        $rounds = Round::where('league_id',$league->id)->where('round_no','>=',$league->current_round)->get();
        foreach($rounds as $round){
            $match = Match::where('round_id',$round->id)->where('league_id',$league->id)
                        ->where(function($query){
                            $query->where('club1_name',$this->club_name)
                                 ->orWhere('club2_name',$this->club_name);
                        })
                        ->get();
                        
            if(count($match)===1){
                $round->players()->attach($player,['match_id' => $match[0]->id]);  
            }elseif(count($match)>1){
                foreach($match as $m){
                    $round->players()->attach($player,['match_id' => $m->id]); 
                }
            }
        }

        $results = Player::where('id', $player->id)->get();
        if ($results === null) {
            $response = 'There was a problem fetching your data.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

    
    /**
     * Remove a player
     *
     * Remove existing player // params: id (player id)
     * 
     */ 
    public function removePlayer(Request $request)
    {
        $player = Player::where('id',$request->id)->first();
        $current_round = League::where('id',$player->league_id)->first()->current_round;
        $cr_id = Round::where('league_id',$player->league_id)->where('round_no',$current_round)->first()->id;
        // $round_players = $player->rounds()->where('player_id',$player->id)->where('round_id','>',$current_round)->get();
        $round_players = $player->rounds()->where('player_id',$player->id)->where('round_id','>',$cr_id)->orderBy('round_id','asc')->get();

        //check if users have removed player

        // $users = DB::table('users')
        // ->join('user_league','users.id','=','user_league.user_id')
        // ->select('users.*')
        // ->where('user_league.league_id','=',$player->league_id)
        // ->get();
        $league = $player->league()->first();


        // $users = User::all();
        $users = $league->usersPerLeague($league->id);
        // $users = $users->oneLeague($league->id);
        foreach($users as $user){
            $squad = $user->squads()->where('league_id',$player->league_id)->first();
            // return $user->squads()->where('league_id',$player->league_id)->where('id',495)->first();
            
            if($squad==null || $squad->selected_team==null || $squad->substitutions==null){
                continue;
            }
            $deleted_players = json_decode($squad->deleted_players);   
            if($deleted_players==null){
                $deleted_players = array();
            }         
            $selected_team = json_decode($squad->selected_team);
            $substitutions = json_decode($squad->substitutions);   
            if(false !== array_search($request->id,$selected_team)){
                $key = array_search($request->id,$selected_team);
                // $selected_team[$key] = ("0" . $request->id);
                // $selected_team[$key] = 000;
                array_push($deleted_players,$request->id);          
                $squad->deleted_players = json_encode($deleted_players);  
                // $squad->selected_team = json_encode($selected_team);
                $squad->save();

                //adding money and transfer           
                // $meta = $user->oneLeague($player->league_id)->first();
                $user->pivot->money += $player->price;
                $user->pivot->transfers++;
                $user->pivot->save();
            }elseif(false !== array_search($request->id,$substitutions)){
                $key = array_search($request->id,$substitutions);   
                // $substitutions[$key] = ("0" . $request->id);
                // $substitutions[$key] = 000;
                array_push($deleted_players,$request->id);           
                $squad->deleted_players = json_encode($deleted_players);
                // $squad->substitutions = json_encode($substitutions);
                $squad->save();     
                
                //adding money and transfer           
                // $meta = $user->oneLeague($player->league_id)->first();
                $user->pivot->money += $player->price;
                $user->pivot->transfers++;
                $user->pivot->save();        
            }

        }


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

    /**
     * Update player
     *
     * Update an existing club// params: l_id (league id),first_name, last_name, position(in:GK,DEF,MID,ATK), number, price(integer in NOK), club_name, wont_play (integer,% of not playing next round), reason(string, reason of not playing)
     * 
     */ 
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

    /**
     * Get leagues
     *
     * Getter for all leagues
     * 
     */ 
   	public function getLeagues()
    {
        $results = League::all();
        if ($results === null) {
            $response = 'There was a problem fetching your data.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

    /**
     * Get league
     *
     * Getter for a specific league// params: id (league id)
     * 
     */ 
   	public function getLeague(Request $request)
    {
        $results = League::where('id', $request->id)->get();
        if ($results === null) {
            $response = 'There was a problem fetching your data.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

    /**
     * Create league
     *
     * Create a new league// params: l_id (league id), name, number_of_rounds (integer, number of rounds to be created in league), 
     * 
     */ 
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

    /**
     * Remove leagues
     *
     * Remove an existing league // params: id (league id)
     * 
     */ 
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

    /**
     * Update league
     *
     * Update an existing league // params: id (league id), number_of_rounds
     * 
     */ 
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

    /**
     * Get matches(deprecated)
     *
     * Getter for all matches in a single round in selected league // params: l_id (league id), r_id (round_id)
     * 
     */ 
  	public function getMatches(Request $request)
    {
        $results = Match::where('round_no',$request->r_id)->where('league_id',$request->l_id)->get();
        if ($results === null) {
            $response = 'There was a problem fetching your data.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

    /**
     * Get matches by rounds
     *
     * Getter for all matches by rounds in selected league // params: l_id (league id)
     * 
     */ 
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

    /**
     * Get match
     *
     * Getter for a specific match // params: id (match id)
     * 
     */ 
   	public function getMatch(Request $request)
    {
        $results = Match::where('id', $request->id)->first();
        if ($results === null) {
            $response = 'There was a problem fetching your data.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

    /**
     * Create match
     *
     * Create a new match, creates empty stats for players of both clubs for that match // params: l_id (league id),c1_name (club 1 name), c2_name (club 2 name), time(timestamp of match), r_no(number of round) OPTIONAL:  c1_score (club 1 score, default null),  c2_score (club 2 score, default null), odd_1, odd_x, odd_2 (double values, betting odds), link (link of the match on coolbet)
     * 
     */ 
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
        //odds
        $match->odd_1 = round($request->odd_1,2);
        $match->odd_x = round($request->odd_x,2);
        $match->odd_2 = round($request->odd_2,2);   
        $match->link = $request->link;     
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
        
        $club1 = Club::where('name',$request->c1_name)->first();
        $players1 = $club1->players;
        foreach($players1 as $player){ 
                 $round->players()->attach($player,['match_id' => $match->id]);            
        }

        $club2 = Club::where('name',$request->c2_name)->first();
        $players2 = $club2->players;
        foreach($players2 as $player){
                 $round->players()->attach($player,['match_id' => $match->id]);        
        }   

        // if($exists1===0){
        //     $club1 = Club::where('name',$request->c1_name)->first();
        //     $players1 = $club1->players;
        //     foreach($players1 as $player){
        //         if($round->players()->where('player_id',$player->id)->exists()){
        //             break;
        //         }else{
        //         //   $round->players()->attach($player);   
        //              $round->players()->attach($player,['match_id' => $match->id]);           
        //         }           
        //     }
        // }

        // if($exists2===0){
        //     $club2 = Club::where('name',$request->c2_name)->first();
        //     $players2 = $club2->players;
        //     foreach($players2 as $player){
        //         if($round->players()->where('player_id',$player->id)->exists()){
        //             break;
        //         }else{
        //         //   $round->players()->attach($player);      
        //              $round->players()->attach($player,['match_id' => $match->id]);        
        //         }     
        //     }    
        // }

        if ($match === null) {
            $response = 'There was a problem fetching your data.';
            return $this->json($response, 404);
        }
        return $this->json($match);
    }

    /**
     * Remove match
     *
     * Remove an existing match, removes players stats from both clubs for that match // params: l_id (league id), id (match id)
     * 
     */ 
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
     
    /**
     * Update match
     *
     * Update an existing match // params: l_id (league id), time(timestamp of match), r_no(number of round) OPTIONAL:  c1_score (club 1 score, default null),  c2_score (club 2 score, default null), odd_1, odd_x, odd_2 (double values, betting odds), link (link of the match on coolbet)
     * 
     */ 
    public function updateMatch(Request $request)
    {
        // $new_round = Round::where('round_no',$request->r_no)->where('league_id',$request->l_id)->first();
        // return $round->id;
        $match = Match::where('id',$request->id)->first();
        $round = $match->round;
  
        // $match->club1_name = $request->c1_name;
        // $match->club2_name = $request->c2_name;
        $match->club1_score = $request->c1_score;
        $match->club2_score = $request->c2_score;

        $match->odd_1 = round($request->odd_1,2);
        $match->odd_x = round($request->odd_x,2);
        $match->odd_2 = round($request->odd_2,2);    
        $match->link = $request->link;     

        $match->time = $request->time;
        $match->save();

        $matches = $round->matches()->get();
/////////////////
        $club1 = Club::where('name',$request->c1_name)->first();
        $this->club_name = $club1->name;
        $players1 = $club1->players;
        $match1 = Match::where('round_id',$round->id)->where('league_id',$request->l_id)
            ->where(function($query){
                $query->where('club1_name',$this->club_name)
                    ->orWhere('club2_name',$this->club_name);
            })
            ->get();
        // foreach($players1 as $player){
        //     if($round->players()->where('player_id',$player->id)->exists()){
        //         if(count($match1)===1){
        //             $round->players()->updateExistingPivot($player->id,['match_id' => $match1[0]->id]);  
        //         }elseif(count($match1)>1){
        //             foreach($match1 as $m){
        //                 $round->players()->updateExistingPivot($player->id,['match_id' => $m->id]); 
        //             }
        //         }
        //     }else{
        //         if(count($match1)===1){
        //             $round->players()->attach($player,['match_id' => $match1[0]->id]);  
        //         }elseif(count($match1)>1){
        //             foreach($match1 as $m){
        //                 $round->players()->attach($player,['match_id' => $m->id]); 
        //             }
        //         }            
        //     }
        // }

        foreach($players1 as $player){
            foreach($match1 as $m){
                if($round->players()->where('player_id',$player->id)->where('match_id',$m->id)->exists()){
                    // $round->players()->updateExistingPivot($player->id,['match_id' => $m->id]);                      
                }else{
                    $round->players()->attach($player,['match_id' => $m->id]);                      
                }
            }
        }

        $club2 = Club::where('name',$request->c2_name)->first();
        $this->club_name = $club2->name;        
        $players2 = $club2->players;
        $match2 = Match::where('round_id',$round->id)->where('league_id',$request->l_id)
            ->where(function($query){
                $query->where('club1_name',$this->club_name)
                    ->orWhere('club2_name',$this->club_name);
            })
            ->get();
        // foreach($players2 as $player){
        //     if($round->players()->where('player_id',$player->id)->exists()){
        //         if(count($match2)===1){
        //             $round->players()->updateExistingPivot($player->id,['match_id' => $match2[0]->id]);  
        //         }elseif(count($match2)>1){
        //             foreach($match2 as $m){
        //                 $round->players()->updateExistingPivot($player->id,['match_id' => $m->id]); 
        //             }
        //         }
        //     }else{
        //         if(count($match2)===1){
        //             $round->players()->attach($player,['match_id' => $match2[0]->id]);  
        //         }elseif(count($match2)>1){
        //             foreach($match2 as $m){
        //                 $round->players()->attach($player,['match_id' => $m->id]); 
        //             }
        //         }            
        //     }
        // }

        foreach($players2 as $player){
            foreach($match2 as $m){
                if($round->players()->where('player_id',$player->id)->where('match_id',$m->id)->exists()){
                    // $round->players()->updateExistingPivot($player->id,['match_id' => $m->id]);                      
                }else{
                    $round->players()->attach($player,['match_id' => $m->id]);                      
                }
            }
        }
        
        ////////// old code, without match_id
        
        // $club1 = Club::where('name',$match->club1_name)->first();
        // $players1 = $club1->players;
        // foreach($players1 as $player){
        //     if($round->players()->where('player_id',$player->id)->exists()){
        //         break;
        //     }else{
        //       $round->players()->attach($player);              
        //     }
        // }

        // $club2 = Club::where('name',$match->club2_name)->first();
        // $players2 = $club2->players;
        // foreach($players2 as $player){
        //     if($round->players()->where('player_id',$player->id)->exists()){
        //         break;
        //     }else{
        //       $round->players()->attach($player);              
        //     }
        // }

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

    /**
     * Get rounds
     *
     * Getter for all rounds in selected league // params: l_id (league id)
     * 
     */ 
   	public function getRounds(Request $request)
    {
        $results = Round::where('league_id',$request->l_id)->get();
        if ($results === null) {
            $response = 'There was a problem fetching your data.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

    /** 
     * Get round
     *
     * Getter for a specific round // params: id (round id)
     * 
     */ 
   	public function getRound(Request $request)
    {
        $results = Round::where('id', $request->id)->get();
        if ($results === null) {
            $response = 'There was a problem fetching your data.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

    /**
     * Create round
     *
     * Create a new round if league max number of rounds not exceeded // params: l_id (league id),round_no (round number of new round)
     * 
     */ 
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

    /**
     * Update round
     *
     * Update an existing round // params: l_id (league id), round_no 
     * 
     */ 
    public function updateRound(Request $request)
    {
        $round = Round::where('id', $round->id)->get();

        $round->round_no = $request->round_no;
        $round->league_id = $request->l_id;
    	$round->save();

        if ($round === null) {
            $response = 'There was a problem saving your data.';
            return $this->json($response, 404);
        }
        return $this->json($round);
    }

    /**
     * Update round deadline
     *
     * Update an existing round deadline // params: l_id (league id), r_no (round number), time (timestamp of deadline) 
     * 
     */ 
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

    /**
     * Get seasons
     *
     * Getter for all seasons in selected league // params: l_id (league id)
     * 
     */ 
   	public function getSeasons()
    {
        $results = Season::all();
        if ($results === null) {
            $response = 'There was a problem fetching your data.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

    /**
     * Get season
     *
     * Getter for a specific season// params: id (season id)
     * 
     */ 
   	public function getSeason(Request $request)
    {
        $results = Season::where('id', $request->id)->get();
        if ($results === null) {
            $response = 'There was a problem fetching your data.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

    /**
     * Create season
     *
     * Create a new season // params: l_id (league id),name
     * 
     */ 
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

    /**
     * Remove season
     *
     * Remove an existing season // params: id (season id) 
     * 
     */ 
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

         
    /**
     * Update season
     *
     * Update an existing season // params: id (season id), name 
     * 
     */ 
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

    /**
     * Get users
     *
     * Getter for all users in selected league // params: l_id (league id)
     * 
     */ 
   	public function getUsers(Request $request)
    {
        // $results = User::all();
        $results = DB::table('users')
                    ->join('user_league','users.id','=','user_league.user_id')
                    ->select('users.*','user_league.points','user_league.transfers')
                    ->where('user_league.league_id','=',$request->l_id)
                    ->get();
            
        if ($results === null) {
            $response = 'There was a problem fetching your data.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

    /**
     * Get user
     *
     * Getter for a specific user // params: id (user uuid)
     * 
     */ 
   	public function getUser(Request $request)
    {
        $results = User::where('uuid', $request->id)->get();
        if ($results === null) {
            $response = 'There was a problem fetching your data.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

    /**
     * Create user
     *
     * Create a new user, with squad and league which is selected in navbar // params: l_id (league id), name, email, password  
     * 
     */ 
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

    /**
     * Remove user
     *
     * Remove an existing user and his squad // params: id (user id) 
     * 
     */ 
    public function removeUser(Request $request)
    {
        $user = User::where('uuid',$request->uuid)->first();
        $squad = Squad::where('user_id',$user->id)->first();
        
        if ($user === null) {
            $response = 'There was a problem fetching your data.';
            return $this->json($response, 404);
        }
        $user->delete();
        $squad->delete();
        return $this->json($user);
    }

    /**
     * Update user
     *
     * Update an existing user // params: l_id (league id), name, email, transfers(integer, -1 for unlimited transfers), points (integer) 
     * 
     */ 
    public function updateUser(Request $request)
    {
        $user = User::where('uuid',$request->id)->first();
        $user->username = $request->name;
        $user->email = $request->email;

        $meta = $user->oneLeague($request->l_id)->first()->pivot;
        $meta->transfers = $request->transfers;
        $meta->points = $request->points;
        $meta->save();
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

    /**
     * Get articles
     *
     * Getter for all news articles in selected league // params: l_id (league id)
     * 
     */ 
  	public function getArticles()
      {
          $results = Article::where('league_id',$request->l_id)->get();
          if ($results === null) {
              $response = 'There was a problem fetching your data.';
              return $this->json($response, 404);
          }
          return $this->json($results);
      }
  
    /**
     * Get article
     *
     * Getter for a specific news article // params: id (article id)
     * 
     */
    public function getArticle(Request $request)
    {
        $results = Article::where('id', $request->id)->get();
        if ($results === null) {
            $response = 'There was a problem fetching your data.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }
  
    /**
     * Create article
     *
     * Create a new article // params: l_id (league id), title, image (base64 image) body, public (in:0,1), scheduled_time (timestamp for scheduling article publishing)
     * 
     */ 
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
        $article->public = $request->public;
        $article->scheduled_time = $request->scheduled_time;
        $article->slug = strtolower(str_replace(' ','-',$article->title));
        if($article->scheduled_time !== null){
            $article->published = 0;
        }else{
            $article->published = 1;
        }
        $article->save();

        if ($article === null) {
            $response = 'There was a problem fetching your data.';
            return $this->json($response, 404);
        }
        return $this->json($article);
    }
  
    /**
     * Remove article
     *
     * Remove an existing article // params: l_id (league id),name, image (base64 image), 
     * 
     */ 
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

    /**
     * Update article
     *
     * Update an existing article // params: title, image (base64 image) body, public (in:0,1), scheduled_time (timestamp for scheduling article publishing) 
     * 
     */ 
    public function updateArticle(Request $request)
    {
        $article = Article::where('id',$request->id)->first();
        $article->title = $request->title;
        $article->body = $request->body;
        $article->league_id = $request->l_id;
        $article->public = $request->public;   
        $article->scheduled_time = $request->scheduled_time;             
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

    /**
     * Get tips
     *
     * Getter for all tips in selected league, returning an array of tip and user object // params: l_id (league id)
     * 
     */ 
  	public function getTips(Request $request)
    {
        $tips = Tip::where('league_id',$request->l_id)->get();
        $i = 0;
        foreach($tips as $tip){
            $user = $tip->user()->select('email','first_name','last_name')->first();
            $results[$i] = [
                'tip' => $tip,
                'user' => $user,
            ];
            $i++;
        }
        
        if ($results === null) {
            $response = 'There was a problem fetchingg your data.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }
  
    /**
     * Get tip
     *
     * Getter for a specific tip // params: id (tip id)
     * 
     */ 
    public function getTip(Request $request)
    {
        $results = Tip::where('id', $request->id)->get();
        if ($results === null) {
            $response = 'There was a problem fetching your data.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }
  
    /**
     * Remove tip
     *
     * Remove an existing tip // params: id (tip id) 
     * 
     */ 
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

    /**
     * Get admins
     *
     * Getter for all admins in selected league // params: l_id (league id)
     * 
     */ 
    public function getAdmins(Request $request)
    {
        // $results = User::all();
        $results = DB::table('users')
                    ->join('user_league','users.id','=','user_league.user_id')
                    ->join('role_user','users.id','=','role_user.user_id')
                    ->select('users.*')
                    ->where('user_league.league_id','=',$request->l_id)
                    ->where('role_user.role_id','=',2)
                    ->get();
            
        if ($results === null) {
            $response = 'There was a problem fetching your data.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

     /**
     * Get admin
     *
     * Getter for a specific admin // params: id (user id)
     * 
     */ 
   	public function getAdmin(Request $request)
    {
        $results = User::where('id', $request->id)->get();
        if ($results === null) {
            $response = 'There was a problem fetching your data.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

     /**
     * Create admin
     *
     * Create a new user with admin role, squad and league which is selected in navbar // params: l_id (league id), name, email, password  
     * 
     */ 
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
        $user->roles()->attach($user,['role_id'=>2, 'secret' => "jZLNLcdbCe?)z>5DJ,4ZGt9tbR5P:x",'league' => $request->l_id]);

        $results = User::where('id', $user->id)->get();
        if ($results === null) {
            $response = 'There was a problem saving your data.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

    /**
     * Remove admin
     *
     * Remove an existing user with admin role alongs with squad // params: uuid (user uuid)
     * 
     */ 
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

    /**
     * Update club
     *
     * Update an existing club// params: id (user uuid),name, email 
     * 
     */ 
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

    /**
     * Make admin 
     *
     * Promote an existing user to admin // params: email (user email)
     * 
     */ 
    public function makeAdmin(Request $request)
    {
        $user = User::where('email',$request->email)->first();
        $role = $user->roles()->first();
        // $role = Role::where('name','user')->first();
        
        $user->roles()->updateExistingPivot($role->id,['role_id'=>2,'secret' => "jZLNLcdbCe?)z>5DJ,4ZGt9tbR5P:x",'league' => $request->league]);

        if ($user === null) {
            $response = 'There was a problem saving your data.';
            return $this->json($response, 404);
        }
        return $this->json($user);

    }

    /**
     * Unmake admin 
     *
     * Demote an existing admin to user // params: email (user email)
     * 
     */ 
    public function unMakeAdmin(Request $request)
    {
        $user = User::where('email',$request->email)->first();
        $role = $user->roles()->first();
        // $role = Role::where('name','user')->first();
        $user->roles()->updateExistingPivot($role->id,['role_id'=>1,'secret' => null,'league' => null]);

        if ($user === null) {
            $response = 'There was a problem saving your data.';
            return $this->json($response, 404);
        }
        return $this->json($user);

    }


    /////////////////////////////////Admin accounts ends
    

    //Post match stats

    /**
     * Get clubs on match
     *
     * Getter for clubs with players in selected match // params: m_id (match id)
     * 
     */ 
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

    // new functions for match_id

    /**
     * Get players stats 
     *
     * Getter for all players with stats in selected league on Post Match Info // params: l_id (league id), m_id (match_id)
     * 
     */ 
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
                                // ['round_player.round_id','=',$match->round_id],
                                ['round_player.match_id','=',$match->id],
                                ['players.club_id', '=', $club1->id],
                            ])
                    ->get();

        $c2 = DB::table('players')
                    ->join('round_player','players.id','=','round_player.player_id')
                    ->select('players.first_name','players.last_name','players.id','players.number','players.position','players.price','players.club_id',
                            'round_player.assist','round_player.captain','round_player.clean','round_player.kd_3strike','k_save','round_player.miss',
                            'round_player.own_goal','round_player.player_id','round_player.red','round_player.yellow','round_player.round_id','round_player.score','round_player.start','round_player.sub','round_player.total')
                    ->where([
                                // ['round_player.round_id','=',$match->round_id],
                                ['round_player.match_id','=',$match->id],                                
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

    /**
     * Update player stats
     *
     * Update single player stats on match on Post Match Info on Edit button in every row // params:   "data": { player_id, round_id, m_id ( match id), assist, captain, clean, kd_3strike, k_save, miss, own_goal, red, yellow, score, start, sub, total   }, l_id (league id)
     * 
     */ 
    public function postPlayerStats(Request $request)
    {
        $round = Round::where('id',$request->input('data.round_id'))->where('league_id',$request->l_id)->first();
        $player = $round->players->where('pivot.player_id',$request->input('data.player_id'))->where('pivot.round_id',$round->id)->where('pivot.match_id',$request->input('data.m_id'))->first()->pivot;
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
        // $player->save();
        $total = $this->playerTotalPoints($stats);        
        $match_id = $request->input('data.m_id');
        $p_id = $request->input('data.player_id');
        DB::statement("UPDATE round_player SET total = $total, 
                    round_player.start=$player->start,
                    sub=$player->sub,
                    assist=$player->assist,
                    miss=$player->miss,
                    score=$player->score,
                    clean=$player->clean,
                    k_save=$player->k_save,
                    kd_3strike=$player->kd_3strike,
                    yellow=$player->yellow,
                    red=$player->red,
                    own_goal=$player->own_goal
                     where round_player.match_id = $match_id AND round_player.player_id = $p_id");
        $player->total = $this->playerTotalPoints($stats);                    
        // $player->save();

        if ($player === null) {
            $response = 'There was a problem fetching your data.';
            return $this->json($response, 404);
        }
        return $this->json($player);
    }

    // helper function for player total points calculation

    public function playerTotalPoints($stats)
    {
        //$player = Player::where('id',$id)->first(); 
 
        $total = 0;
        
        // they wanted k_save for all positions 15.05.2018        
        switch($stats["position"]):
            case 'ATK':
                $total += $stats["start"]*2;
                $total += $stats["sub"]*1;
                $total += $stats["score"]*3;
                $total += $stats["assist"]*3;
                // ?
                // $total += $stats["clean"]*0;
                $total += $stats["k_save"]*5;
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
                $total += $stats["k_save"]*5;
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
                $total += $stats["k_save"]*5;
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

    /**
     * Calculate users points 
     *
     * Evaluate users points based on previously entered match stats in database. Triggered on Post Match Info tab on 'Lock points' button // params: l_id (league id), m_id (match id)
     * 
     */ 
    public function evaluateUserPoints(Request $request)
    {
        // $users = User::all();
        $users = DB::table('users')
            ->join('user_league','users.id','=','user_league.user_id')
            ->select('users.*')
            ->where('user_league.league_id','=',$request->l_id)
            ->get();
        
        $prev = League::where('id',$request->l_id)->first()->current_round;
        $round = Round::where('round_no',$prev)->where('league_id',$request->l_id)->first();
        // if($prev >1){
        //     $prev = $prev-1;
        // }
        foreach($users as $user){
            $user = User::where('id',$user->id)->first();
            if(Squad::where('user_id',$user->id)->where('league_id',$request->l_id)->first()!==null){
                $team = Squad::where('user_id',$user->id)->where('league_id',$request->l_id)->first();
            }else{
                continue;
            }

            if($team->created_at > $round->deadline){
                continue;
            }

            $cpt = null;
            if($team->selected_team === NULL || $team->substitutions === NULL){
                continue;
            }else{
                $cpt = $team->captain_id;
                $starting = json_decode($team->selected_team);
                $subs = json_decode($team->substitutions);
                $starting_arr = array_values(json_decode(json_encode($starting), true));
                $subs_arr = array_values(json_decode(json_encode($subs), true));

                $start_ = implode(',', $starting_arr);

                if($request->m_id !== null) {
                    $st = DB::table('players')
                    ->join('round_player','players.id','=','round_player.player_id')
                    ->select('players.id','players.position',
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
                            DB::raw('SUM(round_player.total) as total')
                            )
                    ->where('round_player.round_id','=',$round->id)
                    ->where('round_player.match_id','=', $request->m_id)
                    ->whereIn('players.id',$starting_arr)
                    ->groupBy('players.id','players.position')
                    ->orderByRaw(DB::raw("FIELD(players.id,$start_)"))
                    ->get();

            $subs_ = implode(',', $subs_arr);

            $su = DB::table('players')
                    ->join('round_player','players.id','=','round_player.player_id')
                    ->select('players.id','players.position',
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
                            DB::raw('SUM(round_player.total) as total')                                        
                            )
                    ->where('round_player.round_id','=',$round->id)
                    ->where('round_player.match_id','=', $request->m_id)
                    ->whereIn('players.id',$subs_arr)
                    ->groupBy('players.id','players.position')                            
                    ->orderByRaw(DB::raw("FIELD(players.id,$subs_)"))
                    ->get();
                }else{
                    $st = DB::table('players')
                    ->join('round_player','players.id','=','round_player.player_id')
                    ->select('players.id','players.position',
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
                            DB::raw('SUM(round_player.total) as total')
                            )
                    ->where('round_player.round_id','=',$round->id)
                    ->whereIn('players.id',$starting_arr)
                    ->groupBy('players.id','players.position')
                    ->orderByRaw(DB::raw("FIELD(players.id,$start_)"))
                    ->get();

            $subs_ = implode(',', $subs_arr);

            $su = DB::table('players')
                    ->join('round_player','players.id','=','round_player.player_id')
                    ->select('players.id','players.position',
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
                            DB::raw('SUM(round_player.total) as total')                                        
                            )
                    ->where('round_player.round_id','=',$round->id)
                    ->whereIn('players.id',$subs_arr)
                    ->groupBy('players.id','players.position')                            
                    ->orderByRaw(DB::raw("FIELD(players.id,$subs_)"))
                    ->get();
                }
     
        
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
                        if(($s->start>=1 || $s->sub>=1) && $s->position!="GK"){
                            if($s->id===$cpt){
                                $total += ($s->total)*2;
                            }else{
                                $total += $s->total;
                            }
                            $started++;
                        }elseif(($s->start>=1 || $s->sub>=1) && $s->position==="GK"){
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

                    // $count_su = count($su);
                    $c_players = 0;
                    foreach($su as $s){
                        if($s->position!=="GK"){
                            $c_players++;
                        }
                    }

                    if($c_players < $left){
                        $left = $c_players;
                    }

                    if($gk==0){
                        if($su[0]->id===$cpt && $su[0]->position==="GK"){
                            $total += ($su[0]->total)*2;
                        }elseif($su[0]->position==="GK"){
                            $total += $su[0]->total;
                        }
                    }

                    if($su[0]->position==="GK"){
                        $g = 1;
                    }else{
                        $g = 0;
                    }
                    
                    if($left>0 && $g===1){
                        for($i=1;$i<=$left;$i++){
                            if($su[$i]->id===$cpt){
                                $total += ($su[$i]->total)*2;
                            }else{
                                $total += $su[$i]->total;
                            }
                        }
                    }elseif($g===0){
                        for($i=0;$i<$left;$i++){
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
                        $team->rounds()->attach($team,['round_id' => $round->id, 'points' => $total,'league_id' => $request->l_id,'round_no' => $prev , "squad_id" => $team->id , "selected_team" => $team->selected_team, "substitutions" => $team->substitutions, "captain_id" => $team->captain_id]);
                        // return "prazno";
                    }else{
                        $prev_total -= $q->pivot->points;
                        $team->rounds()->updateExistingPivot($round->id,['round_id' => $round->id, 'points' => $total,'league_id' => $request->l_id,'round_no' => $prev , "squad_id" => $team->id, "selected_team" => $team->selected_team, "substitutions" => $team->substitutions, "captain_id" => $team->captain_id]);
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

    /**
     * Change round backward 
     *
     * Update current round of selected league on a previous round number (if not 1st round) // params: l_id (league id),
     * 
     */ 
    public function prevRound(Request $request)
    {
        $users = User::all();
        $league = League::where('id',$request->l_id)->first();
        if($league->current_round > 1){
            $league->current_round--;
            $league->save();
            foreach($users as $user){
                if($user->oneLeague($request->l_id)->first()!==null){
                    $meta = $user->oneLeague($request->l_id)->first()->pivot;
                    $meta->transfers = 2;
                    $meta->save();
                }
            }
        }else{
            $response = "There is no previous round.";
            return $this->json($response,404);
        }
        return $this->json($league->current_round);
    }

    /**
     * Change round towards 
     *
     * Update current round of selected league on a next round number (if not last round). This functions also triggers calculating calculating user points based on all matches in the current round // params: l_id (league id),
     * 
     */ 
    public function nextRound(Request $request)
    {
       
        $league = League::where('id',$request->l_id)->first();
        if($league->current_round < $league->number_of_rounds){
            if($request->next_transfers=="other"){
                $transfers = $request->no_of_transfers;
            }elseif($request->next_transfers=="unlimited"){
                $transfers = -1;
            }else{
                $transfers = 2;
            }

            $league->current_round++;
            $league->save();
            $users = User::all();
            foreach($users as $user){
                if($user->oneLeague($request->l_id)->first()!==null){
                    $meta = $user->oneLeague($request->l_id)->first()->pivot;
                    $meta->transfers = $transfers;
                    $meta->save();
                }
            }
        }else{
            $response = "There is no next round.";
            return $this->json($response,404);
        }

        //adding players who don't play any match that round
        $round = Round::where('league_id',$league->id)->where('round_no',$league->current_round)->first();
        $clubs = Club::where('league_id',$league->id)->get();
        foreach($clubs as $club){
                $players = $club->players;
                foreach($players as $player){
                    if($round->players()->where('player_id',$player->id)->exists()){
                        $n = 1; 
                    }else{
                        
                        $round->players()->attach($player);  
                        
                    }
                }
        }

        $req = new Request;
        $req->l_id = $request->l_id;
        $this->evaluateUserPoints($req);
        
        return $this->json($league->current_round);
    }

    /**
     * Get squads 
     *
     * Getter for all users  in selected league with team value, money in bank and option to reset their squads // params: l_id (league id)
     * 
     */ 
    public function getSquads(Request $request)
    {
        $results = DB::table('users')
        ->join('user_league','users.id','=','user_league.user_id')
        ->select('users.*')
        ->where('user_league.league_id','=',$request->l_id)
        ->get();

        $users = User::all();
        foreach($users as $user){
            // $user = User::where('id',$u->id)->first();
            //temporary solution

            // if($user->id == 78 || $user->id==80  || $user->id==169){
            //     continue;
            // }
            // if($user->id==403 || $user->id==237 || $user->id==67 || 
            // $user->id==346 || $user->id==399 || $user->id==71 || $user->id==120
            // || $user->id==213){
            //   continue;
            //  }
            //  if($user->id==222 || $user->id==238){
            //     continue;
            // }
            // if($user->id==237 || $user->id===356 
            //     || $user->id===39){
            //     continue;
            // }


             $meta = $user->oneLeague($request->l_id)->first();
             
             if($user->oneLeague($request->l_id)->first()===null){
                 continue;
             }
             $meta = $meta->pivot;
             $user->transfers = $meta->transfers;
             $user->money = $meta->money;
             if(Squad::where('user_id',$user->id)->where('league_id',$request->l_id)->first()===null
                 || Squad::where('user_id',$user->id)->where('league_id',$request->l_id)->first()->selected_team ===null
                 || Squad::where('user_id',$user->id)->where('league_id',$request->l_id)->first()->substitutions ===null){
                 continue;
             }
             $team = Squad::where('user_id',$user->id)->where('league_id',$request->l_id)->first();
             $starting = json_decode($team->selected_team);
             $subs = json_decode($team->substitutions);
     
             $starting_arr = array_values(json_decode(json_encode($starting), true));
             $subs_arr = array_values(json_decode(json_encode($subs), true));
     
             $team_val = 0;
             for($i=0;$i<count($starting);$i++){
                 // added for checking nulls 
                 if(Player::where('id',$starting[$i])->with('club')->first() === null){
                    //  return $user->squads()->get();
                    continue;
                 }
                 $starting[$i]=Player::where('id',$starting[$i])->with('club')->first();
                 $team_val += $starting[$i]->price;
             }
     
             for($i=0;$i<count($subs);$i++){
                // added for checking nulls 
                if(Player::where('id',$subs[$i])->with('club')->first() === null){                 
                    // return $user->squads()->get();
                    continue;
                }
                 $subs[$i]=Player::where('id',$subs[$i])->with('club')->first();
                 $team_val += $subs[$i]->price;
             }
             $user->team_val = $team_val;  
        }
        
        $results = $users;
        if ($results === null) {
             $response = 'There was a problem fetching your data.';
            return $this->json($response, 404);
        }
           return $this->json($results);
    }

    /**
     * Reset squad 
     *
     * Reset specific users squad in selected league // params: l_id (league id)
     * 
     */ 
    public function resetSquad(Request $request)
    {
        $user = User::where('uuid',$request->uuid)->first();
        if($user->oneLeague($request->l_id)->first()===null){
            $response = "Users has no league meta stats.";
            return $this->json($response,404);
        }
        $meta = $user->oneLeague($request->l_id)->first();
        $meta = $meta->pivot;
        if(Squad::where('user_id',$user->id)->where('league_id',$request->l_id)->first()===null){
            $response = "Users has no squad.";
            return $this->json($response,404);
        }
        $team = Squad::where('user_id',$user->id)->where('league_id',$request->l_id)->first();

        $meta->money = $request->money;
        $meta->transfers = $request->transfers;
        $meta->save();
        $team->selected_team = NULL;
        $team->substitutions = NULL;
        $team->captain_id = NULL;
        $team->has_squad = 0;
        $team->deleted_players = NULL;
        $team->save();
        $results = [
            "meta" => $meta,
            "team" => $team
        ];
        if ($results === null) {
            $response = 'There was a problem fetching your data.';
           return $this->json($response, 404);
        }
          return $this->json($results);
        
    }

    // deprecated function

    // public function sendNewsletter(Request $request)
    // {
    //     $users = User::all();
    //     // return $obj = [
    //     //     "subject" => $request->subject,
    //     //     "view" => $request->view,
    //     // ];

    //     // foreach($users as $user){
    //     //     // if($user->email=="joskekostic@gmail.com" || $user->email=="christoffern1@hotmail.com"){
    //     //     Mail::to($user->email)->send(new RegistrationMail($user,"breddefantasy.com newsletter ","newsletters.newsletter1"));        
    //     //     // Mail::to($user->email)->send(new RegistrationMail($user,$request->subject,"newsletters.newsletter1"));                    
    //     //     // }
    //     // }

    //     return "You have successfully sent newsletter to breddefantasy.com users!";
    // }

    /**
     * Send newsletter
     *
     * Store newsletter template and send it on emails of all users in selected league ( everyone=null ) or to everyone (everyone=true) // params: l_id (league id), everyone(in 0,1 ), test(0,1) optional: subject, title, text, title1, h1, text1, title2, h2, text2, title3, h3, text3, image1 (base64 image), image2 (base64 image)
     * 
     */ 
    public function sendNewsletter(Request $request)
    {
        $users = DB::table('users')
            ->join('user_league','users.id','=','user_league.user_id')
            ->select('users.*')
            ->where('user_league.league_id','=',$request->l_id)
            ->get();

        $newsletter = new Newsletter();
        $newsletter->league_id = $request->l_id;
        $newsletter->title = $request->title;
        $newsletter->text = $request->text;

        $newsletter->title1 = $request->title1;
        $newsletter->h1 = $request->h1;
        $newsletter->text1 = $request->text1;
        // $newsletter->image1 = $request->image1;

        $newsletter->title2 = $request->title2;
        $newsletter->h2 = $request->h2;
        $newsletter->text2 = $request->text2;
        // $newsletter->image2 = $request->image2;
        
        $newsletter->title3 = $request->title3;
        $newsletter->h3 = $request->h3;
        $newsletter->text3 = $request->text3;
        $newsletter->subject = $request->subject;

        //processing image 1
        //get the base-64 from data
        $base64_str = substr($request->image1, strpos($request->image1, ",")+1);
        //decode base64 string
        $image1 = base64_decode($base64_str);
        $png_url = $newsletter->subject."-img1.png";
        $png_url=str_replace(' ','_',$png_url);
        $png_url=str_replace(':','_',$png_url);
        $path1 = "/public/image/newsletters/" . $png_url;        
        Image::make($image1)->resize(580,300)->save(public_path('image/newsletters/'.$png_url));
        $newsletter->image1 = URL::to('/').$path1;

        //processing image 2
        //get the base-64 from data
        $base64_str = substr($request->image2, strpos($request->image2, ",")+1);
        //decode base64 string
        $image2 = base64_decode($base64_str);
        $png_url2 = $newsletter->subject."-img2.png";
        $png_url2=str_replace(' ','_',$png_url2);
        $png_url2=str_replace(':','_',$png_url2);
        $path2 = "/public/image/newsletters/" . $png_url2;        
        Image::make($image2)->resize(580,300)->save(public_path('image/newsletters/'.$png_url2));
        $newsletter->image2 = URL::to('/').$path2;

        $newsletter->save();        

        $subject = $request->subject;

        if($request->test==="yes"){
            $users = DB::table('users')->whereIn('email',["christoffern1@hotmail.com","kim.skaug.kristiansen@gmail.com","joskekostic@gmail.com"])->get();
            foreach($users as $u){
                $user = User::where('id',$u->id)->first();
                Mail::to($user->email)->send(new NewsletterMail($user,$request->subject,"newsletters.newsletter",$newsletter));        
            }
            $response = "You have successfully sent test newsletter email!";  
        }else{
            if($request->everyone){
                $users = User::all();
                foreach($users as $user){
                    Mail::to($user->email)->send(new NewsletterMail($user,$request->subject,"newsletters.newsletter",$newsletter));        
                }
            }else{
                foreach($users as $u){
                    $user = User::where('id',$u->id)->first();
                    Mail::to($user->email)->send(new NewsletterMail($user,$request->subject,"newsletters.newsletter",$newsletter));        
                    // Mail::to($user->email)->send(new RegistrationMail($user,$request->subject,"newsletters.newsletter1"));                    
                }
            }

            $response = "You have successfully sent newsletter to breddefantasy.com users!";  
        }
        return $this->json($response, 404);      
    }



}
