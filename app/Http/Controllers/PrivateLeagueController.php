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
use Illuminate\Support\Facades\DB;
use JWTAuth;
use Mail as Mail;
use App\Mail\LeagueMail as LeagueMail;

class PrivateLeagueController extends Controller
{
    public function createLeague(Request $request)
    {
        $league = League::where('id',$request->l_id)->first();

        $faker = Faker::create();
        $user = JWTAuth::authenticate();
        $pl = new PrivateLeague;
        $pl->name = $request->name;
        $pl->owner_id = $user->id;
        $pl->league_id = $request->l_id;
        // $pl->start_round = $request->start;
        $pl->start_round = $league->current_round;
        $pl->code = $faker->md5();
        $emails = array();
        array_push($emails,$user->email);
        $pl->emails = json_encode($emails);
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

    
    public function getPrivateLeagues(Request $request)
    {
        $user = JWTAuth::authenticate();
        $pl_o = PrivateLeague::where('owner_id',$user->id)->where('league_id',$request->l_id)->get();
        

        $meta = $user->oneLeague($request->l_id)->first();
        $joined = $meta->pivot->joined_privates;
        
        $pl_j = array();
        if($joined!==null){
            $jids = json_decode($joined);
            $jids = array_values(json_decode(json_encode($jids), true));
            $i = 0;
            foreach($jids as $jid){
                $pl_j[$i] = PrivateLeague::where('id',$jid)->first();
                $i++;
            }
        }

        $results = [
            "owned" => $pl_o,
            "joined"=> $pl_j
        ];
        
        if ($results === null) {
            $response = 'There was a problem fetching players.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

    public function leaveLeague(Request $request)
    {
        $user = JWTAuth::authenticate();
        $pl = PrivateLeague::where('id',$request->id)->first();
      //  return $pl;
        $emails = json_decode($pl->emails);
        $emails = array_values(json_decode(json_encode($emails), true));
        
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
        if ($key === null) {
            $response = 'There is no such a players in your league.';
            return $this->json($response, 404);
        }
        
        unset($emails[$key]);
        $emails = json_encode($emails);
        $pl->emails = $emails;
        $pl->save();

        $meta = $user->oneLeague($pl->league_id)->first();
        $joined = $meta->pivot->joined_privates;
        $joined = json_decode($joined);
        if(!empty($joined)){
               $joined = array_values(json_decode(json_encode($joined), true));    
        }

        
        $key2 = array_search($pl->id,$joined);
        if ($key2 === null) {
            $response = 'There is no such a players in this league.';
            return $this->json($response, 404);
        }
        unset($joined[$key2]);
        
        if($joined!==null){
            $meta->pivot->joined_privates = json_encode($joined);
        }
        $meta->pivot->privates--;
        $meta->pivot->save();



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
            $response = 'There was a problem fetching league.';
            return $this->json($response, 404);
        }
        $emails = json_decode($pl->emails);
        if (count($emails)>1) {
            $response = 'There are still players in your league.';
            return $this->json($response, 404);
        }
        $pl->delete();
        return $this->json($pl);    
    }

    public function joinLeague(Request $request)
    {
        $user = JWTAuth::authenticate();        
        $pl = PrivateLeague::where('code',$request->code)->first();
        $emails = json_decode($pl->emails);

        if($user->id==$pl->owner_id){
            $response = "You are the owner of the league.";
            return $this->json($response, 404);
        }
        
        if(empty($emails)){
            $emails = array();
            $search=null;
        }else{
            $emails = array_values(json_decode(json_encode($emails), true));
            $search = array_search($user->email,$emails);
        }

            if($search==true){
                $response = 'You have already joined the league.';
                return $this->json($response, 404);
            }
        
         $invites = json_decode($pl->invites);
        if(empty($invites)){
            $invites = array();
            $search=null;
        }else{
            $invites = array_values(json_decode(json_encode($invites), true));            
            $search = array_search($user->email,$invites);
            if($search===false){
                $response = "You are not invited to this league!";
                return $this->json($response);
            }
            unset($invites[$search]);
        }
            // if($search==true){
            //     $response = 'You have already joined the league.';
            //     return $this->json($response, 404);
            // }

        if($request->code == $pl->code){
            
            array_push($emails,$user->email);
        }
        foreach($emails as $email){
        $receiver = User::where('email',$email)->first();
        // Mail::to($email)->send(new LeagueMail($user, $receiver, $pl,"New invite on breddefantasy.com,  $user->username has invited you to his private league!","emails.league"));
        }
        
        $emails = json_encode($emails);
        $invites = json_encode($invites);
        $pl->emails = $emails;
        $pl->invites = $invites;
        $pl->save();

        $meta = $user->oneLeague($pl->league_id)->first();

        $joined = $meta->pivot->joined_privates;
        $joined = json_decode($joined);
        
        if($joined===null){
            $joined = array();
        }else{
            $joined = array_values(json_decode(json_encode($joined), true));
        }

        array_push($joined,$pl->id);

        $meta->pivot->joined_privates = json_encode($joined);
        $meta->pivot->privates++;
        $meta->pivot->save();

        if ($pl === null) {
            $response = 'There was a problem with league code.';
            return $this->json($response, 404);
        }
        return $this->json($pl); 
    }

    public function joinLeagueLink($code)
    {
        $user = JWTAuth::authenticate();        
        $pl = PrivateLeague::where('code',$request->code)->first();
        $emails = json_decode($pl->emails);

        if($user->id==$pl->owner_id){
            $response = "You are the owner of the league.";
            return $this->json($response, 404);
        }
        
        if(empty($emails)){
            $emails = array();
            $search=null;
        }else{
            $emails = array_values(json_decode(json_encode($emails), true));
            $search = array_search($user->email,$emails);
        }

            if($search==true){
                $response = 'You have already joined the league.';
                return $this->json($response, 404);
            }
        
         $invites = json_decode($pl->invites);
        if(empty($invites)){
            $invites = array();
            $search=null;
        }else{
            $invites = array_values(json_decode(json_encode($invites), true));            
            $search = array_search($user->email,$invites);
            if($search===false){
                $response = "You are not invited to this league!";
                return $this->json($response);
            }
            unset($invites[$search]);
        }
        if($request->code == $pl->code){
            
            array_push($emails,$user->email);
        }
        // foreach($emails as $email){
        // $receiver = User::where('email',$email)->first();
        // // Mail::to($email)->send(new LeagueMail($user, $receiver, $pl,"New invite on breddefantasy.com,  $user->username has invited you to his private league!","emails.league"));
        // }
        
        $emails = json_encode($emails);
        $invites = json_encode($invites);
        $pl->emails = $emails;
        $pl->invites = $invites;
        $pl->save();

        $meta = $user->oneLeague($pl->league_id)->first();

        $joined = $meta->pivot->joined_privates;
        $joined = json_decode($joined);
        
        if($joined===null){
            $joined = array();
        }else{
            $joined = array_values(json_decode(json_encode($joined), true));
        }

        array_push($joined,$pl->id);

        $meta->pivot->joined_privates = json_encode($joined);
        $meta->pivot->privates++;
        $meta->pivot->save();

        if ($pl === null) {
            $response = 'There was a problem with league code.';
            return $this->json($response, 404);
        }
        return $this->json($pl); 

    }

    public function sendInvite(Request $request)
    {
        $user = JWTAuth::authenticate();
        // $pl = PrivateLeague::where('owner_id',$user->id)->first();
        $pl = PrivateLeague::where('id',$request->id)->first();
        $code = $pl->code;
        // $emails = json_decode($pl->emails);

        $invites = json_decode($pl->invites);
        
        if($invites===null){
            $invites = array();
        }else{
             $invites = array_values(json_decode(json_encode($invites), true));            
        }
        $invalid = array();
        $new_invs = $request->email;
       
        foreach($new_invs as $new_inv){
            if(array_search($new_inv,$invites)==true){
                array_push($invalid,$new_inv);
            }else{
                array_push($invites,$new_inv);
                $receiver = User::where('email',$new_inv)->first();
                Mail::to($receiver->email)->send(new LeagueMail($user, $receiver, $pl,"New invite on breddefantasy.com,  $user->username has invited you to his private league!","emails.league"));
            }
            // Mail::to
           
            
        }
        $invites = json_encode($invites);
        $pl->invites = $invites;
        $pl->save();
        


        if ($pl === null) {
            $response = 'There are still players in your league.';
            return $this->json($response, 404);
        }
        return $this->json($pl);
        
    }

    public function getTable(Request $request)
    {
        $pl = PrivateLeague::where('id',$request->id)->first();
        $start = $pl->start_round;
        $emails = json_decode($pl->emails);
        $emails = array_values(json_decode(json_encode($emails), true));
        

        $score = DB::table('users')
                ->join('squads','users.id','=','squads.user_id')
                ->join('squad_round','squads.id','=','squad_round.squad_id')
                ->select('users.first_name','users.last_name','users.username','users.email',
                        'squad_round.round_no','squad_round.points', 'squad_round.points')
                ->whereIn('users.email',$emails)
                ->where('squad_round.league_id','=',$pl->league_id)
                ->groupBy('squad_round.round_no')
                ->having('squad_round.round_no','>=',$start)
                ->get();

        if ($score === null) {
            $response = 'There is no such a players in your league.';
            return $this->json($response, 404);
        }
        return $this->json($score);      
    }

    public function banUser(Request $request)
    {
        // $user = JWTAuth::authenticate();
        $pl = PrivateLeague::where('id',$request->id)->first();
        $user = User::where('email',$request->email)->first();
        if($pl->owner_id===$user->id){
            $response = "Can't ban admin!";
            return $this->json($response,404);
        }
        $emails = json_decode($pl->emails);
        $emails = array_values(json_decode(json_encode($emails), true));
        

        $meta = $user->oneLeague($pl->league_id)->first();
        
        $joined = json_decode($meta->pivot->joined_privates);
        $joined = array_values(json_decode(json_encode($joined), true));

        $key_mail = array_search($request->email,$joined);
        unset($joined[$key_mail]);
        $joined = json_encode($joined);
        $meta->pivot->joined_privates = $joined;
        $meta->pivot->privates--;
        $meta->pivot->save();

        
        $key = array_search($request->email, $emails);
        if ($key === null) {
            $response = 'There is no such a players in your league.';
            return $this->json($response, 404);
        }
        unset($emails[$key]);
        $emails = json_encode($emails);
        $pl->emails = $emails;
        $pl->save();
        return $this->json($pl);
    }

    public function showTable(Request $request)
    {
        $pl = PrivateLeague::where('name',$request->name)->first();
        $emails = json_decode($pl->emails);
        $emails = array_values(json_decode(json_encode($emails), true));        
        if($emails===null){
            $response = "There is no other players in this league.";
            return $this->json($response, 404);
        }
        
        $total_sorted = DB::table('users')
                ->join('user_league','users.id','=','user_league.user_id')
                ->join('private_leagues','private_leagues.league_id','=','user_league.league_id')
                ->select('users.username','user_league.points','users.email')
                ->whereIn('users.email',$emails)
                ->where('user_league.league_id','=',$pl->league_id)
                ->where('private_leagues.id','=',$pl->id)
                ->orderBy('user_league.points','desc')
                ->get();

        $round_no = intval($request->gw);
        
        $by_rounds = DB::table('users')
                ->join('user_league','users.id','=','user_league.user_id')                
                ->join('squads','users.id','=','squads.user_id')
                ->join('squad_round','squads.id','=','squad_round.squad_id')
                ->select('users.username','user_league.points as total','squad_round.points','users.email','squad_round.round_no')
                ->whereIn('users.email',$emails)
                ->where('squads.league_id','=',$pl->league_id)
                ->where('squad_round.round_no','=',$round_no) 
                ->orderBy('user_league.points','desc')                
                ->orderBy('squad_round.points','desc')
                ->orderBy('users.id','desc')
                // ->having('round_no','>',$pl->start_round)                
                ->get();

        // return $by_rounds;
    
        $res = [
            "sorted" => $total_sorted,
            "gameweeks" => $by_rounds
        ];

        //?
        // $results = array_combine($usernames,$points);
        //return $results;
        
     
        if ($res === null) {
            $response = 'There is no such a players in your league.';
            return $this->json($response, 404);
        }
        return $this->json($res);
    }



}
