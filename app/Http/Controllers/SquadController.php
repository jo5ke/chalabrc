<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Club as Club;
use App\User as User;
use App\Squad as Squad;
use App\Player as Player;
use App\League as League;
use App\Transfer as Transfer;
use App\Round as Round;
use JWTAuth;
use Illuminate\Support\Facades\DB;

/**
 * @resource Squad
 *
 * User squads routes, My Team & Transfer page controller
 */
class SquadController extends Controller
{

    // big function for sending all the data about logged in player's team
   
    /**
     * Get my team 
     *
     * Getter for all info(user,ranking,squad with players,current round deadline,squad value) on My team page for authorized user (jwt auth token required) // params: l_id (league id)
     * 
     */ 
    public function getMyTeamPage(Request $request)
    {
        $user = JWTAuth::authenticate();
        $meta = $user->leagues->where('id',$request->l_id)->first();
        $meta = $meta->pivot;
                    
        $league = League::where('id',$request->l_id)->first();
        $deadline = Round::where('league_id',$league->id)->where('round_no',$league->current_round)->first()->deadline;
        
        $team = Squad::where('user_id',$user->id)->where('league_id',$request->l_id)->first();
        $starting = json_decode($team->selected_team);
        $subs = json_decode($team->substitutions);

        $starting_arr = array_values(json_decode(json_encode($starting), true));
        $subs_arr = array_values(json_decode(json_encode($subs), true));

        $team_val = 0;
        // for($i=0;$i<count($starting);$i++){
        //     $starting[$i]=Player::where('id',$starting[$i])->with('club')->first();
        //     $team_val += $starting[$i]->price;
        // }

        // for($i=0;$i<count($subs);$i++){
        //     $subs[$i]=Player::where('id',$subs[$i])->with('club')->first();
        //     $team_val += $subs[$i]->price;
        // }

        //Checking if there's removed player in a team

        $deleted_players = null;
        if($team->deleted_players !== null){
            $deleted_players = array();
            $i = 0;
            foreach(json_decode($team->deleted_players) as $dp){
                $deleted_players[$i++] = Player::withTrashed()->where('id',$dp)->first();
            }
        }
        
        // foreach($starting_arr as $start){
        //     if(!Player::where('id',$start)->exists()){

        //     }
        // }
        // foreach($subs_arr as $sub){
        //     if(!Player::where('id',$sub)->exists()){

        //     }
        // }

        $start_ = implode(',', $starting_arr);
        $starting_stats = DB::table('players')
            ->join('clubs','players.club_id','=','clubs.id')
            ->join('round_player','players.id','=','round_player.player_id')
            ->select('players.id','players.first_name','players.last_name','players.position','players.price','players.wont_play','players.reason','players.league_id','players.number','clubs.name as club_name'
                    ,DB::raw('SUM(round_player.score) as total_score'),DB::raw('SUM(round_player.assist) as total_assist'),DB::raw('SUM(round_player.clean) as total_clean'),DB::raw('SUM(round_player.yellow) as total_yellow'),DB::raw('SUM(round_player.red) as total_red'),DB::raw('SUM(round_player.total) as total'))
            ->whereIn('players.id',$starting_arr)
            ->whereNull('deleted_at')
            ->groupBy('players.id','players.first_name','players.last_name','players.position','players.price','players.wont_play','players.reason','players.league_id','clubs.name','players.number')
            ->orderByRaw(DB::raw("FIELD(players.id,$start_)"))
            ->get();

        $subs_ = implode(',', $subs_arr);
        $subs_stats = DB::table('players')
            ->join('clubs','players.club_id','=','clubs.id')
            ->join('round_player','players.id','=','round_player.player_id')
            ->select('players.id','players.first_name','players.last_name','players.position','players.price','players.wont_play','players.reason','players.league_id','players.number' ,'clubs.name as club_name'
                    ,DB::raw('SUM(round_player.score) as total_score'),DB::raw('SUM(round_player.assist) as total_assist'),DB::raw('SUM(round_player.clean) as total_clean'),DB::raw('SUM(round_player.yellow) as total_yellow'),DB::raw('SUM(round_player.red) as total_red'),DB::raw('SUM(round_player.total) as total'))
            ->whereNull('deleted_at')
            ->whereIn('players.id',$subs_arr)
            ->groupBy('players.id','players.first_name','players.last_name','players.position','players.price','players.wont_play','players.reason','players.league_id','clubs.name','players.number')
            ->orderByRaw(DB::raw("FIELD(players.id,$subs_)"))
            ->get();

        $results = [ 
            "user" => $user,
            "meta" => $meta,
            "team" => $team,
            "squad_value" => $team_val,
            "players" => [
                "starting" => $starting_stats,
                "subs" => $subs_stats
            ],
            "deadline" => $deadline,
            "current_round" => $league->current_round,
            "deleted_players" => $team->deleted_players,
            "del" => $deleted_players,
        ];
        if ($results === null) {
            $response = 'There was a problem fetching players.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

    //getting All Players from all Clubs
    
    /**
     * Get all players with stats
     *
     * Getter for all footballers with stats on Transfer and Statistics pages // params: l_id (league id)
     * 
     */ 
    public function getPlayers(Request $request)
    {
        $pstats = DB::table('players')
                ->join('clubs','players.club_id','=','clubs.id')
                ->join('round_player','players.id','=','round_player.player_id')
                ->select('players.id','players.first_name','players.last_name','players.position','players.price','players.wont_play','players.reason','players.league_id','players.number' ,'clubs.name as club_name',
                        DB::raw('SUM(round_player.sub) as total_sub'),DB::raw('SUM(round_player.start) as total_start'),DB::raw('SUM(round_player.score) as total_score'),DB::raw('SUM(round_player.assist) as total_assist'),DB::raw('SUM(round_player.clean) as total_clean'),DB::raw('SUM(round_player.yellow) as total_yellow'),DB::raw('SUM(round_player.red) as total_red'),DB::raw('SUM(round_player.total) as total') )
                ->where('clubs.league_id','=',$request->l_id)
                ->whereNull('deleted_at')
                ->groupBy('round_player.player_id','players.first_name','players.last_name','players.id','players.position','players.price','players.wont_play','players.reason','players.league_id','clubs.name','players.number')
                ->get();

        $users = DB::table('users')
                ->join('user_league','users.id','=','user_league.user_id')
                ->join('squads','users.id','=','squads.user_id')
                ->select('users.username','squads.selected_team','squads.substitutions','squads.league_id')
                ->where('user_league.league_id','=',$request->l_id)
                ->where('squads.league_id','=',$request->l_id)
                ->get();
        
        $total_users = count($users);
        // antiqueandarts.com
        foreach($pstats as $pstat){
            $belongs = 0;
            foreach($users as $user){
                if($user->selected_team === null){
                    continue;
                }else{
                    $selected_team = json_decode($user->selected_team); 
                }
                if($user->substitutions === null){
                    continue;
                }else{
                    $substitutions = json_decode($user->substitutions);
                }
                if(in_array($pstat->id,$selected_team)){
                    $belongs++;
                }elseif(in_array($pstat->id,$substitutions)){
                    $belongs++;
                }
            }
            $per_user = round($belongs/$total_users*100,2);

            $pstat->total = intval($pstat->total);
            $pstat->total_red = intval($pstat->total_red);
            $pstat->total_yellow = intval($pstat->total_yellow);
            $pstat->total_clean = intval($pstat->total_clean);
            $pstat->total_assist = intval($pstat->total_assist);
            $pstat->total_start = intval($pstat->total_start);
            $pstat->total_sub = intval($pstat->total_sub);
            $pstat->total_score = intval($pstat->total_score);
            $pstat->per_user = round($per_user,2);
        }

        if ($pstats === null) {
            $response = 'There was a problem fetching players.';
            return $this->json($response, 404);
        }
        return $this->json($pstats);
    }

    /**
     * Get all clubs
     *
     * Getter for all clubs // params: l_id (league id)
     * 
     */ 
    public function getClubs(Request $request)
    {
        $clubs = Club::where('league_id',$request->l_id)->get();
        if ($results === null) {
            $response = 'There was a problem fetching players.';
            return $this->json($response, 404);
        }
        return $this->json($clubs);
    }

    /**
     * Post user squad
     *
     * Create user's squad after registration (jwt auth token required) // params: l_id (league id), selected_team(array[11] of ids of starting 11), substitutions(array[4] of ids of 4 subs), name (optional, squad name)
     * 
     */ 
    public function postSquad(Request $request)
    {

        $starting_ids = json_encode($request->selected_team);
        $subs_ids = json_encode($request->substitutions);
        // $full_squad = array_merge($starting_ids,$subs_ids);

        $user = JWTAuth::authenticate();
        $squad = Squad::where('user_id',$user->id)->where('league_id',$request->l_id)->first();
        
        $squad->name = $request->name;
        $squad->formation = "4-4-2";
        $squad->selected_team = $starting_ids;
        $squad->substitutions = $subs_ids;
        $squad->league_id = $request->l_id;
        $squad->has_squad = true;
        $squad->save();

        $money = $request->money;
        $league = League::where('id',$request->l_id)->first();

        // $sq =  $user->squads->first();
        // $user->leagues()->updateExistingPivot($user->id,['money' => $money]);
        $user->leagues()->updateExistingPivot($request->l_id,['money' =>  $request->money]);
        

        if ($squad === null) {
            $response = 'There was a problem fetching players.';
            return $this->json($response, 404);
        }
        return $this->json($squad);
    }


    // dodati array igraca na terenu i array zamena

    /**
     * Update user squad
     *
     * Update user's squad on My team page or after resetting team (jwt auth token required) // params: l_id (league id), selected_team(array[11] of ids of starting 11), substitutions(array[4] of ids of 4 subs), name (optional, squad name), captain (optional, id of captain)
     * 
     */ 
    public function updateSquad(Request $request)
    {
        $starting_ids = json_encode($request->selected_team);
        $subs_ids = json_encode($request->substitutions);
        //$full_squad = array_merge($starting_ids,$subs_ids);
        

        $user = JWTAuth::authenticate();
  
     //   $user = User::where('uuid',$uuid)->first();
        $squad = $user->squads->where('league_id',$request->l_id)->first();
    //    $players = $squad->players;

        $squad->formation = $request->formation;
        $squad->selected_team = $starting_ids;
        $squad->substitutions = $subs_ids;
        $squad->user_id = $user->id;
        $squad->league_id = $request->l_id;
        $squad->captain_id = $request->captain[0];
        $squad->save();
        

        if ($squad === null) {
            $response = 'There was a problem fetching players.';
            return $this->json($response, 404);
        }
        return $this->json($squad);
    }

    /**
     * Make transfer
     *
     * Sell and buy player(s) on Transfer page (jwt auth token required) // params: l_id (league id), buy(array of ids of bought players),sell(array of ids of selled players)
     * 
     */ 
    public function makeTransfer(Request $request)
    {
        // $buy = json_decode(json_encode($request->buy));
        // $sell = json_decode(json_encode($request->sell));
        $buy = $request->buy;
        $sell = $request->sell;

        $user = JWTAuth::authenticate();
        $meta = $user->oneLeague($request->l_id)->first();
   
        $team = Squad::where('user_id',$user->id)->where('league_id',$request->l_id)->first();
        $selected_team = json_decode($team->selected_team);
        $substitutions = json_decode($team->substitutions);
        $deleted_players = null;
        if($team->deleted_players !== null)
            $deleted_players = json_decode($team->deleted_players);
  
        $transfer = new Transfer;
        $transfer->user_id = $user->id;
        $transfer->squad_id = $team->id;
        $transfer->league_id = $request->l_id;
        $transfer->buy = json_encode($request->buy);
        $transfer->sell = json_encode($request->sell);
        $transfer->round_no = League::where('id',$request->l_id)->first()->current_round;
        $transfers_left = $meta->pivot->transfers;

        $buyed = array();
        $selled = array();
        $amm_buy =0;
        $amm_sell =0;

        for($i=0;$i<count($buy);$i++)
        {
            $buyed[$i] = Player::where('id',$buy[$i])->first();
            $amm_buy += $buyed[$i]->price;
        }
        for($i=0;$i<count($sell);$i++)
        {
            $selled[$i] = Player::withTrashed()->where('id',$sell[$i])->first();
            $amm_sell += $selled[$i]->price;                
        }
        $transfer->ammount_buy = $amm_buy;   
        $transfer->ammount_sell = $amm_sell;
        $meta->pivot->money -= $amm_buy;           
        $meta->pivot->money += $amm_sell; 
        $point_cost = $meta->pivot->points - $request->points;
        $meta->pivot->points = $request->points;

        $i=0;
        foreach($sell as $s){
            // $selac = array();
            if($buy[$i]===null){
                $response = "Player does not exist";
                return $this->json($response,404);
            }
            if($deleted_players !== null){
                if(in_array($s,$deleted_players)){
                    $key = array_search($s,$deleted_players);  
                    // return $deleted_players;
                    array_splice($deleted_players, $key, 1);
                    if(empty($deleted_players)){
                        $deleted_players = null;
                    }
                }
            }
            if(in_array($s,$selected_team)){
                // array_push($selac,$s);
                $key = array_search($s,$selected_team);                
                // $selected_team = array_diff($selected_team, $selac);
                // $selected_team = array_values(json_decode(json_encode($selected_team), true));   
                // array_push($selected_team,$buy[$i]);
                $selected_team[$key] = $buy[$i];                
                $i++;   
            }elseif(in_array($s,$substitutions)){
                // array_push($selac,$s);
                // $substitutions = array_diff($substitutions, $selac);
                // $substitutions = array_values(json_decode(json_encode($substitutions), true));   
                // array_push($substitutions,$buy[$i]); 
                $key = array_search($s,$substitutions);                    
                $substitutions[$key] = $buy[$i];                
                $i++;
            }else{
                $response = "Missing player in your team!";
                return $this->json($response,404);
            }
        }

        if($transfers_left != -1){
            if(count($buy) <= $transfers_left){
                $meta->pivot->transfers = $transfers_left - count($buy);
                // $meta->pivot->save();
            }else{
                $meta->pivot->transfers = 0;
                // $last_transfer = null;
                // if($squad->transfers()->where('league_id',$league->id)->where('round_no',$league->current_round)->latest()->first() !== null)
                //     $last_transfer = $squad->transfers()->where('league_id',$league->id)->where('round_no',$league->current_round)->latest()->first()->points;
                // else
                //     $last_transfer = 0;
                $transfer->points = $point_cost;
                // $meta->pivot->save();     
            }
        }
        $meta->pivot->save();     

        // $meta->pivot->save();     
        $transfer->save();
        // $meta->pivot->money = $request->money;
        // $meta->pivot->save();  
        $team->selected_team = json_encode($selected_team);
        $team->substitutions = json_encode($substitutions);
        if($deleted_players !== null)
            $team->deleted_players = json_encode($deleted_players);
        else
            $team->deleted_players = null;
        $team->save();

        // $transfer = Transfer::where('user_id',$user->id)->where('squad_id',$team->id)->where('league_id',$request->l_id)->get();
        // $no_of_transfers = $transfer->count();
        if ($team === null) {
            $response = 'There was a problem fetching players.';
            return $this->json($response, 404);
        }
        return $this->json($team);
    }

    //deprecated function
    public function sellPlayer(Request $request)
    {
        $user = User::where('id',1)->first();
        $meta = $user->leagues->where('id',$request->l_id)->first();

        $player = Player::where('id',$request->p_id)->first();
        //$meta->pivot->money -= 100;
        $meta->pivot->money += $player->price;
        $meta->pivot->save();

        $team = Squad::where('user_id',$user->id)->where('league_id',$request->l_id)->first();
        $team->players()->detach($player,['user_id' => $user->id]);


        $meta2 = $meta->pivot;
        return $user->with('squads.players')->get();
    }

    /**
     * Check transfers
     *
     * Check number of free transfers left, get how many points was cost of last transfers (jwt auth token required) // params: l_id (league id)
     * 
     */ 
    public function checkTransfer(Request $request)
    {
        $league = League::where('id',$request->l_id)->first();
        $user = JWTAuth::authenticate();
        $squad = Squad::where('user_id',$user->id)->where('league_id',$request->l_id)->first();

        $meta = $user->oneLeague($request->l_id)->first();
        
        // if($league->current_round == 1 ){
        //     $meta->pivot->transfers = 2;
        //     $meta->pivot->save();
        // } 
        
        $transfer = $meta->pivot->transfers;
        // $no_of_transfers = 0;
        // if($transfer == 0){
        //     $transfers = $squad->transfers()->where('league_id',$league->id)->where('round_no',$league->current_round)->get();
        //     foreach($transfers as $t){
        //         $no_of_transfers += count(json_decode($t->buy));
        //     }
        //     return $no_of_transfers;
        // }
        $last_transfer = null;
        if($squad->transfers()->where('league_id',$league->id)->where('round_no',$league->current_round)->latest()->first() !== null)
            $last_transfer = $squad->transfers()->where('league_id',$league->id)->where('round_no',$league->current_round)->latest()->first()->points;
        else
            $last_transfer = 0;
        
        $results = [
            "transfers" => $transfer,
            "current_round" => $league->current_round,
            "points" => $last_transfer
        ];

        if ($results === null) {
            $response = 'There was a problem fetching players.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

    /**
     * Get next round
     *
     * Get next round with matches // params: l_id (league id)
     * 
     */ 
    public function getNextRound(Request $request)
    {
        $league = League::where('id', $request->l_id)->first();
        $next = $league->current_round;
        $round = Round::where('round_no',$next)->where('league_id',$request->l_id)->with('matches')->first();

        if ($round === null) {
            $response = 'There was a problem fetching a round.';
            return $this->json($response, 404);
        }
        return $this->json($round);
    }

    /**
     * Check squad
     *
     * Check if user's squad has bought 15 players (jwt auth token required) // params: l_id (league id)
     * 
     */ 
    public function hasSquad(Request $request)
    {
        $user = JWTAuth::authenticate();
        $team = Squad::where('user_id',$user->id)->where('league_id',$request->l_id)->first();
        $flag = $team->has_squad;

        if ($flag === null) {
            $response = 'There was a problem fetching a has squad flag.';
            return $this->json($response, 404);
        }
        return $this->json($flag);
    }

    // database fix function
    
    public function transferRevert(Request $request)
    {
        return "no access";
        $transfers = Transfer::where('league_id',1)->where('created_at','>','2018-04-15 10:59:43')->get();
        foreach($transfers as $t)
        {
                    // $t = Transfer::where('id',848)->first();
                    $squad = Squad::where('id',$t->squad->id)->first();
                    $selected_team = json_decode($squad->selected_team);
                    $substitutions = json_decode($squad->substitutions);
    
                    $sell = json_decode($t->sell);
                    $buy = json_decode($t->buy);
    
                    $i = 0;
                    foreach($buy as $s){
                        $selac = array();
                        if(in_array($s,$selected_team)){
                            array_push($selac,$s);
                            $key = array_search($s,$selected_team);
                            // $selected_team = array_diff($selected_team, $selac);
                            // $selected_team = array_values(json_decode(json_encode($selected_team), true));   
                            // array_push($selected_team,$sell[$i]);
                            $selected_team[$key] = $sell[$i];
                            $i++;   
                        }else{
                            array_push($selac,$s);         
                            $key = array_search($s,$substitutions);                    
                            // $substitutions = array_diff($substitutions, $selac);
                            // $substitutions = array_values(json_decode(json_encode($substitutions), true));   
                            // array_push($substitutions,$sell[$i]); 
                            $substitutions[$key] = $sell[$i];
                            $i++;
                        }
                    }
                    $squad->selected_team = json_encode($selected_team);
                    $squad->substitutions = json_encode($substitutions);
                    $squad->save();
        }

    }

}
