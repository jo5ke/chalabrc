<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User as User;
use App\Squad as Squad;
use JWTFactory;
use JWTAuth;
use Validator;
use Response;
use Faker\Factory;

class APIRegisterController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255|unique:users',
            'username' => 'required',
            'password'=> 'required'
        ]);
        if ($validator->fails()) {
            return $this->json($validator->errors(),404);
            // return response()->json($validator->errors());
        }
        User::create([
            'first_name' => $request->get('first_name'),
            'last_name' => $request->get('last_name'),
            'username' => $request->get('username'),
            'email' => $request->get('email'),
            'password' => bcrypt($request->get('password')),
        ]);
     //   $user = User::first();
        $user = User::where('email', $request->email)->first();
        $user->uuid = Factory::create()->uuid;
        $user->roles()->attach($user,['role_id'=>1]);
        $user->save(); 
        $squad = new Squad;
        $squad->user_id = $user->id;
        $squad->league_id = $request->league;
        $squad->save();
        $user->leagues()->attach($user,['money' => 100000 ,'points' => 0,'league_id'=>$request->league ,'squad_id'=> $squad->id]);
        $token = JWTAuth::fromUser($user);
        
     //  return Response::json(compact('token'));
     //   return response()->json($user);
         return $this->json($user);
    }
}

// jZLNLcdbCe?)z>5DJ,4ZGt9tbR5P:x