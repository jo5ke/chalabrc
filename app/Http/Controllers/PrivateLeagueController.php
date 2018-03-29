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
        $pl_o = PrivateLeague::where('owner_id',$user->id)->get();

        $meta = $user->oneLeague($request->l_id)->first();
        $joined = $meta->pivot->joined_privates;
        
        $jids = json_decode($joined);
        $i = 0;
        $pl_j = array();
        foreach($jids as $jid){
            $pl_j[$i] = PrivateLeague::where('id',$jid)->first();
            $i++;
        }

        $results = [
            "owned" => $pl_o,
            "joined"=> $pl_j
        ];
        
        if ($results === null) {
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
        $emails = json_encode($emails);
        $pl->emails = $emails;
        $pl->save();
        return $pl;
        // helper

        // $starting = json_decode($team->selected_team);
        // $selected_team = array_values(json_decode(json_encode($selected_team), true));  
        
    }

    //add condition to check if all users are kicked first, check if emails null
    public function deleteLeague(Request $request)
    {
      //  $user = JWTAuth::authenticate();
        $pl = PrivateLeague::where('id',$request->id)->first();
        
        if ($pl === null) {
            $response = 'There was a problem fetching players.';
            return $this->json($response, 404);
        }
        if ($pl->emails != null) {
            $response = 'There are still players in your league.';
            return $this->json($response, 404);
        }
        $pl->delete();
        return $this->json($pl);    
    }

    public function joinLeague($code)
    {
        $user = JWTAuth::authenticate();        
        $pl = PrivateLeague::where('code',$code)->first();
        $emails = json_decode($pl->emails);

        if($code == $pl->code){
            array_push($emails,$user->email);
        }
        
        $emails = json_encode($emails);
        $pl->emails = $emails;
        $pl->save();

        if ($pl === null) {
            $response = 'There was a problem with league code.';
            return $this->json($response, 404);
        }
        return $this->json($pl); 
    }

    public function sendInvite(Request $request)
    {
        $user = JWTAuth::authenticate();
        $pl = PrivateLeague::where('owner_id',$user->id)->first();
        $code = $pl->code;
        $emails = json_decode($pl->emails);

        $invites = json_decode($pl->invites);
        array_push($invites,$request->email);
        $invites = json_encode($invites);
        $pl->invites = $invites;
        $pl->save();
        


        if ($emails != null) {
            $response = 'There are still players in your league.';
            return $this->json($response, 404);
        }
        // Mail::to
        
    }

    public function banUser(Request $request)
    {
        $user = JWTAuth::authenticate();
        $pl = PrivateLeague::where('owner_id',$user->id)->first();
        $emails = json_decode($pl->emails);

        $key = array_search($request->email,$emails);
        if ($key != null) {
            $response = 'There is no such a players in your league.';
            return $this->json($response, 404);
        }
        unset($emails[$key]);
        $emails = json_encode($emails);
        $pl->emails = $emails;
        $pl->save();
        return $this->json($pl);
    }

    public function showTable($id)
    {
        $pl = PrivateLeague::where('name',$request->name)->first();
        $emails = json_decode($pl->emails);

        $users= array();
        $i=0;
        foreach($emails as $email){
            $users[$i] = User::where('email',$email)->first();
            $i++;
        }
        
        $j=0;
        foreach($users as $user){
            $points[$j] = $user->oneLeague($pl->league_id)->pivot;
            $j++;
        }

        if ($points != null) {
            $response = 'There is no such a players in your league.';
            return $this->json($response, 404);
        }
        return $this->json($points);
    }



}
