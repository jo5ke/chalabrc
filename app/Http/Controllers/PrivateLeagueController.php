<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\League as League;
use App\Round as Round;
use App\Player as Player;
use App\Season as Season;
use App\User as User;
use App\PrivateLeague;
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
        $pl->code = $faker->md5();
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

    public function leaveLeague(Request $request)
    {
        $user = JWTAuth::authenticate();
        $pl = PrivateLeague::where('id',$request->id)->first();
        $emails = json_decode($pl->emails);
        return $pl;
        
        //     $emails = array_values(json_decode(json_encode($emails), true));
        //  //   $mail = array_values($emails);
        //     return $emails;

        // $i=0;
        // $mail = array();
        // foreach($emails as $email){
        //     $mail[$i] = $email;
        //     $i++;
        // }

        $key = array_search($user->email,$emails);
        
        unset($emails[$key]);
        // $emails = json_encode($emails);
        $pl->emails = $emails;
        $pl->save();
        return $pl;
        // helper

        // $starting = json_decode($team->selected_team);
        // $selected_team = array_values(json_decode(json_encode($selected_team), true));  
        
    }

    public function deleteLeague(Request $request)
    {
      //  $user = JWTAuth::authenticate();
        $pl = PrivateLeague::where('id',$request->id)->first();
        
        if ($pl === null) {
            $response = 'There was a problem fetching players.';
            return $this->json($response, 404);
        }
        $pl->delete();
        return $this->json($pl);    
    }



}
