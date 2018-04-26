<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use JWTAuth;
use Faker\Factory as Faker;
use Mail as Mail;
use App\Mail\RegistrationMail;
use App\Mail\SendTip;
use Carbon\Carbon;
use App\User as User;

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
            "birthdate"  => $user->birthdate,
            "zip"        => $user->zip,
            "address"    => $user->address,
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
        $user->phone = $request->phone;
        $user->address = $request->address;
        $user->city = $request->city;
        $user->zip = $request->zip;
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

    public function sendResetPassword(Request $request)
    {
        $user = User::where('email',$request->email)->first();
        // $league = League::where('id',$request->l_id)->first();
        $faker = Faker::create();
        $token = $faker->sha1();

        DB::table('password_resets')->insert([
            ['email' => $request->email , 'token' => $token, 'created_at' => Carbon::now() ]
        ]);

        if ($user === null) {
            $response = 'User with that email does not exist.';
            return $this->json($response, 404);
        }


        Mail::to($user->email)->send(new RegistrationMail($user,"Password reset request on breddefantasy.com!","emails.forget_password",$token));      
        // Mail::to($user->email)->send(new SendTip($user,"Password reset request on breddefantasy.com!",$token ,"emails.registration"));
        return $this->json($user);
    }

    public function getNewPassword($token)
    {
        $email = DB::table('users')
                    ->join('password_resets','users.email','=','password_resets.email')
                    ->select('users.email')
                    ->where('password_resets.token','=',$token)
                    ->get();

        $user = User::where('email',$email)->first();
        if ($user === null) {
            $response = 'Invalid request.';
            return $this->json($response, 404);
        }
        return $this->json($user);
    }

    public function confirmPassword(Request $request)
    {
        $user = User::where('email',$request->email)->first();

        $email = DB::table('password_resets')
                    ->where('password_resets.email','=',$request->email)
                    ->delete();

        if($request->new_password==$request->new_password_confirm)
            {
                // $user->password = Hash::make($request->new_password);
                $user->password = bcrypt($request->new_password);
                $user->save();
            }else{
                $response = "Password doesn't match";
                return $this->json($response, 404);
            }
        return $this->json($user);
    }
}
