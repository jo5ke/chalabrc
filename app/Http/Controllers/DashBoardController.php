<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\News as News;
use App\User as User;
use App\League as League;


class DashBoardController extends Controller
{
    //get all the rounds
    public function getRounds()
    {
        $results = Round::all();
        if ($results->isEmpty()) {
            $response = 'There was a problem fetching your data.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

    // getter for a matches in a round
    // "r_id" = round id
    public function getMatchesByRound(Request $request)
    {
        $results = Match::where('round_id',$request->r_id);
        if ($results->isEmpty()) {
            $response = 'There was a problem fetching your data.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

    


}
