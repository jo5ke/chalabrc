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

class APILoginController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password'=> 'required'
        ]);
        if ($validator->fails()) {
            return $this->json($validator->errors());            
            // return response()->json($validator->errors());
        }
        $credentials = $request->only('email', 'password');
        $user = User::where('email', $request->email)->first();
        $token = JWTAuth::fromUser($user);
        try {
            if (! $token ) {
                return $this->json(['error' => 'invalid_credentials'], 401);
           //     return response()->json(['error' => 'invalid_credentials'], 401);
            }
        } catch (JWTException $e) {
            return $this->json(['error' => 'could_not_create_token'], 500);
            // return response()->json(['error' => 'could_not_create_token'], 500);
        }
        
        $role_id = $user->roles()->first();
        $role = Role::where('id',$role_id->pivot->role_id)->first();
        $secret = $role_id->pivot->secret;

        if($secret === null){
            $response = [
                'user' => $user,
                'token' => $token,
                'role' => $role->name
                ];
        }else{
            $response = [
                'user' => $user,
                'token' => $token,
                'role' => $role->name,
                'secret' => $secret
                ];
        }

        return $this->json($response);
     //   return response()->json($response);
    }
}