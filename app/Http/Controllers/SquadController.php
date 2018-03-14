<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Club as Club;
use App\User as User;


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
        if ($results->isEmpty()) {
            $response = 'There was a problem fetching players.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

    // big function for sending all the data about current player's team
    public function getMyTeam(Request $request)
    {
        $user = auth()->user()->first();
        $meta = $user->with('league')->where('user_id', $user->id)->where('league_id',$request->l_id)->get();
        $team = Squad::where('user_id',$user->id)->where('league_id',$request->l_id)->first();
        $players = Player::where('squad_id', $team->id)->where('user_id', $user->id)->get();
        $results = [ 
            "user" => $user,
            "team" => $team,
            "players" => $players
        ];
        if ($results->isEmpty()) {
            $response = 'There was a problem fetching players.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

    // getting all the info about the player(for lists and popups)
    // "p_id" get route for getting the player info;
    public function getPlayerInfo(Request $requests)
    {
        $user = auth()->user()->first();
        $players = Player::all();
        $player = $players->where('user_id',$user->id)->where('player_id', $p_id)->first();
        if ($results->isEmpty()) {
            $response = 'There was a problem fetching players.';
            return $this->json($response, 404);
        }
        return $this->json($player);
    }

    //getting All Players from all Clubs
    public function getAllPlayers()
    {
        $players = Player::all();
        if ($results->isEmpty()) {
            $response = 'There was a problem fetching players.';
            return $this->json($response, 404);
        }
        return $this->json($players);
        
        
    }

    //  get the info and its players
    // "l_id" - league id
    public function getAllClubs(Request $request)
    {
        $clubs = Club::where('league_id',$request->l_id)->get();
        if ($results->isEmpty()) {
            $response = 'There was a problem fetching players.';
            return $this->json($response, 404);
        }
        return $this->json($clubs);
    }

    public function getClub(Request $request)
    {
        $club = Club::where('league_id',$request->l_id)->get();
        if ($results->isEmpty()) {
            $response = 'There was a problem fetching players.';
            return $this->json($response, 404);
        }
        return $this->json($club);
    }



    

}
