<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Club as Club;
use App\User as User;
use App\Squad as Squad;
use App\Player as Player;
use App\League as League;
use App\Transfer as Transfer;
use JWTAuth;


class SquadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    // "l_id" = id lige, 
    public function getSquad()
    {
        $user = auth()->user();
        $results = Squad::where('league_id', $request->l_id)->where('user_id',$user->id);
        if ($results === null) {
            $response = 'There was a problem fetching players.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

    // big function for sending all the data about current player's team
    public function getMyTeamPage(Request $request)
    {
        $user = JWTAuth::authenticate();
        $meta = $user->leagues->where('id',$request->l_id)->first();
        $meta = $meta->pivot;
        
        $team = Squad::where('user_id',$user->id)->where('league_id',$request->l_id)->first();
        $starting = json_decode($team->selected_team);
        $subs = json_decode($team->substitutions);

        $team_val = 0;
        

        for($i=0;$i<count($starting);$i++){
            $starting[$i]=Player::where('id',$starting[$i])->with('club')->first();
            // $team_val += $starting[$i]->price;
        }

        for($i=0;$i<count($subs);$i++){
            $subs[$i]=Player::where('id',$subs[$i])->with('club')->first();
            // $team_val += $starting[$i]->price;
        }

     //   $play = Squad::where('user_id',$user->id)->where('id',$team->id)->with('players')->first();
        $play = $team->players;
    //    $players = Player::where('squad_id', $team->id)->where('user_id', $user->id)->get();
        $results = [ 
            "user" => $user,
            "meta" => $meta,
            "team" => $team,
            "squad_value" => $team_val,
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

    // getting all the info about the player(for lists and popups)
    // "p_id" get route for getting the player info;
    public function getPlayer(Request $request)
    {
        $user = auth()->user()->first();
        $players = Player::all();
        $player = $players->where('user_id',$user->id)->where('player_id', $p_id)->first();
        if ($results === null) {
            $response = 'There was a problem fetching players.';
            return $this->json($response, 404);
        }
        return $this->json($player);
    }

    //getting All Players from all Clubs
    public function getPlayers(Request $request)
    {
        $players = League::where('id',$request->l_id)->with('players')->get();
     //   $players = Player::all();
        if ($players === null) {
            $response = 'There was a problem fetching players.';
            return $this->json($response, 404);
        }
        return $this->json($players);
    }

    //  get the info and its players
    // "l_id" - league id
    public function getClubs(Request $request)
    {
        $clubs = Club::where('league_id',$request->l_id)->get();
        if ($results === null) {
            $response = 'There was a problem fetching players.';
            return $this->json($response, 404);
        }
        return $this->json($clubs);
    }

    public function getClub(Request $request)
    {
        $club = Club::where('league_id',$request->l_id)->get();
        if ($results === null) {
            $response = 'There was a problem fetching players.';
            return $this->json($response, 404);       
        }
        return $this->json($club);
    }

    public function postSquad(Request $request)
    {

        $starting_ids = json_encode($request->selected_team);
        $subs_ids = json_encode($request->substitutions);
        // $full_squad = array_merge($starting_ids,$subs_ids);

        $user = JWTAuth::authenticate();
        $squad = Squad::where('user_id',$user->id)->where('league_id',$request->l_id)->first();
        
        $squad->formation = "4-4-2";
        $squad->selected_team = $starting_ids;
        $squad->substitutions = $subs_ids;
        $squad->league_id = $request->l_id;
        $squad->save();

        $money = $request->money;
        $league = League::where('id',$request->l_id)->first();

        // $sq =  $user->squads->first();
        // $user->leagues()->updateExistingPivot($user->id,['money' => $money]);
        $user->leagues()->updateExistingPivot($request->l_id,['money' =>  $request->money]);
        
        // $meta = $user->leagues->where('id',$request->l_id)->first();
        // $meta = $meta->pivot;
        // $squad = $user->squads->first();
        // $players = $squad->players;
        // for($i=0;$i<count($full_squad);$i++){
        //     $player = Player::where('id',$full_squad[$i])->first();
        //     $squad->updatePlayers($user->id)->attach($player,['user_id' => $user->id]);    
        // }
        // $players = $squad->players;

        if ($squad === null) {
            $response = 'There was a problem fetching players.';
            return $this->json($response, 404);
        }
        return $this->json($squad);
    }


    // dodati array igraca na terenu i array zamena
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

    public function makeTransfer(Request $request)
    {
        // $buy = json_encode($request->buy);
        // $sell = json_encode($request->sell);
        $buy = $request->buy;
        $sell = $request->sell;

        $user = JWTAuth::authenticate();
        // $user = User::where('id',1)->first();
        $meta = $user->oneLeague($request->l_id)->first();
        

        $team = Squad::where('user_id',$user->id)->where('league_id',$request->l_id)->first();
        $selected_team = json_decode($team->selected_team);
        $substitutions = json_decode($team->substitutions);
  


        $transfer = new Transfer;
        $transfer->user_id = $user->id;
        $transfer->squad_id = $team->id;
        $transfer->league_id = $request->l_id;
        $transfer->buy = json_encode($request->buy);
        $transfer->sell = json_encode($request->sell);

        if(count($buy) >= 2){
            $b1 = Player::where('id',$buy[0])->first();
            $b2 = Player::where('id',$buy[1])->first();  
            $s1 = Player::where('id',$sell[0])->first();
            $s2 = Player::where('id',$sell[1])->first();   
            $meta->pivot->transfers = 0;
            $meta->pivot->money -= $b1->price;         
            $meta->pivot->money -= $b2->price;  
            $transfer->ammount_buy = $b1->price + $b2->price;   
            $meta->pivot->money += $s1->price;         
            $meta->pivot->money += $s2->price; 
            $transfer->ammount_sell = $s1->price + $s2->price;    
            $meta->pivot->save();
            // if(count($selected_team)==11){     
            //     array_push($selected_team, $buy[0], $buy[1]); 
            // }elseif(count($selected_team)==12){
            //     array_push($selected_team, $buy[0]);   
            //     array_push($substitutions, $buy[0]);   
            // }else{
            //     array_push($substitutions, $buy[0], $buy[1]); 
            // }  
            if(in_array($sell[0],$selected_team)){
                $selac = [$sell[0]];
                $selected_team = array_diff($selected_team, $selac);
                $selected_team = array_values(json_decode(json_encode($selected_team), true));
                array_push($selected_team,$buy[0]);
            }else{
                $selac = [$sell[0]];
                $substitutions = array_diff($substitutions, $selac);
                $substitutions = array_values(json_decode(json_encode($substitutions), true));
                array_push($substitutions,$buy[0]);                
            }
            if(in_array($sell[1],$selected_team)){
                $selac = [$sell[1]];
                $selected_team = array_diff($selected_team, $selac);
                $selected_team = array_values(json_decode(json_encode($selected_team), true));  
                array_push($selected_team,$buy[1]);                              
            }else{
                $selac = [$sell[1]];
                $substitutions = array_diff($substitutions, $selac);
                $substitutions = array_values(json_decode(json_encode($substitutions), true));    
                array_push($substitutions,$buy[1]);                                           
            }
        }elseif(count($buy)==1){
            $b1 = Player::where('id',$buy[0])->first();
            $s1 = Player::where('id',$sell[0])->first();
            $meta->pivot->transfers--;
            $meta->pivot->money -= $b1->price;    
            $transfer->ammount_buy = $b1->price;       
            $meta->pivot->money += $s1->price;   
            $transfer->ammount_sell = $s1->price;      
            $meta->pivot->save();     
            // if(count($selected_team)==12){     
            //     array_push($selected_team, $buy[0]); 
            // }else{ 
            //     array_push($substitutions, $buy[0]);   
            // }
            $selac = [$sell[0]];
            if(in_array($sell[0],$selected_team)){
                $selected_team = array_diff($selected_team, $selac);
                $selected_team = array_values(json_decode(json_encode($selected_team), true));    
                array_push($selected_team,$buy[0]);                            
            }else{
                $substitutions = array_diff($substitutions, $selac);
                $substitutions = array_values(json_decode(json_encode($substitutions), true));  
                array_push($substitutions,$buy[0]);                                                              
            }
        }
        $transfer->save();
        // $meta->pivot->money = $request->money;
        // $meta->pivot->save();  
        $team->selected_team = json_encode($selected_team);
        $team->substitutions = json_encode($substitutions);
        $team->save();

        // $transfer = Transfer::where('user_id',$user->id)->where('squad_id',$team->id)->where('league_id',$request->l_id)->get();
        // $no_of_transfers = $transfer->count();

        if ($team === null) {
            $response = 'There was a problem fetching players.';
            return $this->json($response, 404);
        }
        return $this->json($team);
    }

    //old not-used function
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

    public function checkTransfer(Request $request)
    {
        $user = JWTAuth::authenticate();
        // $user = User::where('id',1)->first();
        $meta = $user->oneLeague($request->l_id)->first();
        $transfer = $meta->pivot->transfers;

        if ($transfer === null) {
            $response = 'There was a problem fetching players.';
            return $this->json($response, 404);
        }
        return $this->json($transfer);
    }



    

}
