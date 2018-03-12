<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Hash as Hash;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a custom registration system. 
    |
    */

    public function register(Request $request){
        return $request;

        $request->validate([
            'username'        => 'required',
            'email'             => 'required',
            'password'          => 'required',
            'confirm_password'  => 'required',
        ]);
        if(User::where('email', '=', Input::get('email')->count() > 0) {
            return $response = array('error' => 1,'message' => 'User with this email already exists.' );
        }
        if (!strcmp(Input::get('password'), Input::get('confirm_password'))) {
            $user = new User(Input::all());
            $user->password = Hash::make(Input::get('password'));
            $user->save();
            return $response = array('error' => 0,'message' => 'You have been registered.');
        } else {
            return Redirect::back()->with('error', 'Email or password do not match!');
            }
        }
}
