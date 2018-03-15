<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Club;
use App\Match;

class AdminController extends Controller
{
	// DO NOT FORGET TO ADD RELATIONS TO QUERIES ONCE EVERYTHING IS DEFINED
   	public function getClubs()
    {
        $results = Club::all();
        if ($results->isEmpty()) {
            $response = 'There was a problem fetching your data.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

   	public function getClub(Request $request)
    {
        $results = Club::where('id', $request->id)->get();
        if ($results->isEmpty()) {
            $response = 'There was a problem fetching your data.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

   	public function getLeagues()
    {
        $results = League::all();
        if ($results->isEmpty()) {
            $response = 'There was a problem fetching your data.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

   	public function getLeague(Request $request)
    {
        $results = League::where('id', $request->id)->get();
        if ($results->isEmpty()) {
            $response = 'There was a problem fetching your data.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

    public function postLeague(Request $request)
    {
    	$match = new League();
    	$league->name = $request->name;
    	$league->number_of_rounds = $request->number_of_rounds;
    	$league->save();

        $results = League::where('id', $match->id)->get();
        if ($results->isEmpty()) {
            $response = 'There was a problem saving your data.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

  	public function getMatches()
    {
        $results = Match::all();
        if ($results->isEmpty()) {
            $response = 'There was a problem fetching your data.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

   	public function getMatch(Request $request)
    {
        $results = Match::where('id', $request->id)->get();
        if ($results->isEmpty()) {
            $response = 'There was a problem fetching your data.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

   	public function postMatch(Request $request)
    {
    	$match = new Match();
    	$match->club1_id = $request->club1_id;
    	$match->club2_id = $request->club2_id;
    	$match->score1  = $request->score1;
    	$match->score2  = $request->score2;
    	$match->round_id = $request->round_id;
    	$match->save();

        $results = Match::where('id', $match->id)->get();
        if ($results->isEmpty()) {
            $response = 'There was a problem fetching your data.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

   	public function getRounds()
    {
        $results = Round::all();
        if ($results->isEmpty()) {
            $response = 'There was a problem fetching your data.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

   	public function getRound(Request $request)
    {
        $results = Round::where('id', $request->id)->get();
        if ($results->isEmpty()) {
            $response = 'There was a problem fetching your data.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

    public function postRound(Request $request)
    {
    	$round = new Round();
    	$round->save();

        $results = Round::where('id', $round->id)->get();
        if ($results->isEmpty()) {
            $response = 'There was a problem saving your data.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

   	public function getSeasons()
    {
        $results = Season::all();
        if ($results->isEmpty()) {
            $response = 'There was a problem fetching your data.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

   	public function getSeason(Request $request)
    {
        $results = Season::where('id', $request->id)->get();
        if ($results->isEmpty()) {
            $response = 'There was a problem fetching your data.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

    public function postSeason(Request $request)
    {
    	$season = new Season();
    	$season->save();

        $results = Season::where('id', $season->id)->get();
        if ($results->isEmpty()) {
            $response = 'There was a problem saving your data.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

   	public function getUsers()
    {
        $results = User::all();
        if ($results->isEmpty()) {
            $response = 'There was a problem fetching your data.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

   	public function getUser(Request $request)
    {
        $results = User::where('id', $request->id)->get();
        if ($results->isEmpty()) {
            $response = 'There was a problem fetching your data.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }
}
