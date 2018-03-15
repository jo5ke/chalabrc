<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\News as News;
use App\User as User;
use App\League as League;


class LeagueController extends Controller
{
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
}
