<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\League as League;
use App\Round as Round;
use App\Player as Player;
use App\Season as Season;
use App\User as User;
use App\PrivateLeague as PrivateLeague;
use Faker\Factory as Faker;
use JWTAuth;

class PrivateLeagueController extends Controller
{
    public function createLeague(Request $request)
    {
        $faker = Faker::create();
        $user = JWTAuth::authenticate();
        $pl = new PrivateLeague;
        $pl->name = $request->name;
        $pl->owner_id = $user->id;
        $pl->league_id = $request->l_id;
     //   $pl->started = 
        $pl->code = $faker->biasedNumberBetween($min = 10, $max = 20, $function = 'sqrt');
        $pl->save();
        
        $meta = $user->oneLeague($request->l_id)->first();
        $meta->pivot->privates++;
        $meta->pivot->save();

        if ($pl === null) {
            $response = 'There was a problem fetching players.';
            return $this->json($response, 404);
        }
        return $this->json($pl);
    }

    public function getPrivateLeagues()
    {
        $user = JWTAuth::authenticate();
        $pl = PrivateLeague::where('owner_id',$user->id)->get();

        if ($pl === null) {
            $response = 'There was a problem fetching players.';
            return $this->json($response, 404);
        }
        return $this->json($pl);
    }

}
