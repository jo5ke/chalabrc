<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Club;

class AdminController extends Controller
{
	// DO NOT FORGET TO ADD RELATIONS TO QUERIES ONCE EVERYTHING IS DEFINED
   	public function getClubs()
    {
        $results = Club::all();
        if ($results->isEmpty()) {
            $response = 'There was a problem fetching players.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

   	public function getClub(Request $request)
    {
    	// return $request->id;
        $results = Club::where('id', $request->id)->get();
        // return $results;
        if ($results->isEmpty()) {
            $response = 'There was a problem fetching players.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

   	public function getLeagues()
    {
        $results = League::all();
        if ($results->isEmpty()) {
            $response = 'There was a problem fetching players.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

   	public function getLeague(Request $request)
    {
    	// return $request->id;
        $results = League::where('id', $request->id)->get();
        // return $results;
        if ($results->isEmpty()) {
            $response = 'There was a problem fetching players.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

  	public function getMatches()
    {
        $results = Match::all();
        if ($results->isEmpty()) {
            $response = 'There was a problem fetching players.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

   	public function getMatch(Request $request)
    {
    	// return $request->id;
        $results = Match::where('id', $request->id)->get();
        // return $results;
        if ($results->isEmpty()) {
            $response = 'There was a problem fetching players.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }
}
