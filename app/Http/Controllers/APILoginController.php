<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use JWTFactory;
use JWTAuth;
use App\User;
use App\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Mail as Mail;
use App\Mail\RegistrationMail;

/**
 * @resource Login
 *
 * User login route
 */
class APILoginController extends Controller
{
    /**
     * Login route
     *
     * User login function, returning token,user's info,role,leagues  // params: email, password
     * 
     */ 
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password'=> 'required'
        ]);
        if ($validator->fails()) {
            return $this->json($validator->errors());            
        }
        $token=null;
        $credentials = $request->only('email', 'password');
        $user = User::where('email', $request->email)->first();
        if(Hash::check($request->password,$user->password)){    
        $token = JWTAuth::fromUser($user);
        }
        try {
            if (! $token ) {
                return $this->json(['error' => 'invalid_credentials'], 401);
            }
        } catch (JWTException $e) {
            return $this->json(['error' => 'could_not_create_token'], 500);
        }
        
        $role_id = $user->roles()->first();
        $role = Role::where('id',$role_id->pivot->role_id)->first();
        
         $leagues = DB::table('users')
                        ->join('user_league','users.id','=','user_league.user_id')
                        ->select('user_league.league_id')
                        ->where('user_league.user_id',$user->id)
                        ->get();

        $i=0;
        foreach($leagues as $league){
            $league_ids[$i] = intval($league->league_id);
            $i++;
        }
        
        if($role->name === "user" ){
            $response = [
                'user' => $user,
                'token' => $token,
                'role' => $role->name,
                'leagues' => $league_ids        
                ];
        }else{
            $secret = $role_id->pivot->secret;  
            $a_leagues = $role_id->pivot->league;  
            $i=0;
            $a_league = array();
            array_push($a_league,$a_leagues);
            $response = [
                'user' => $user,
                'token' => $token,
                'role' => $role->name,
                'leagues' => $league_ids,
                'secret' => $secret,
                'admin' =>  $a_league
                ];
        }
        
      return $this->json($response);
    }
}