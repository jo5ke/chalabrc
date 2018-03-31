<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use JWTAuth;

class UserController extends Controller
{
    //
    public function getHome()
    {
        return view('home');
    }

    public function getUserSettings()
    {
        $user = JWTAuth::authenticate();
        $results = [
            "first_name" => $user->first_name,
            "last_name"  => $user->last_name,
            "city"       => $user->city,
            "country"    => $user->country,
            "birthdate"  => $user->birthdate   
        ];

        if ($results === null) {
            $response = 'There was a problem updating your data.';
            return $this->json($response, 404);
        }
        return $this->json($results);
    }

    public function updateUserSettings(Request $request)
    {
        $user = JWTAuth::authenticate();
        $user->birthdate = $request->birthdate;
        $user->country = $request->country;
        $user->city = $request->city;
        $user->first_name = ucwords($request->first_name);
        $user->last_name = ucwords($request->last_name);
        // $full_name = $request->full_name;
        // $full_name = explode(" ",$full_name);
        $user->save();

        if ($user === null) {
            $response = 'There was a problem updating your data.';
            return $this->json($response, 404);
        }
        return $this->json($user);
    }

    public function changePassword(Request $request)
    {
        $user = JWTAuth::authenticate();
        if(Hash::check($request->old_password,$user->password)){  
            if($request->new_password==$request->new_password_confirm)
            {
                $user->password = Hash::make($request->new_password);
                $user->save();
            }else{
                $response = "Password doesn't match";
                return $this->json($response, 404);
            }
        }else{
            $response = 'Incorrect old password.';
            return $this->json($response, 404);
        }

        return $this->json($user);  
    }
}
