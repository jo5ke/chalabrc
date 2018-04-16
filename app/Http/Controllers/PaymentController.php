<?php

namespace App\Http\Controllers;
use Stripe;

use Illuminate\Http\Request;
use JWTAuth;
use App\Squad as Squad;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function payment(Request $request)
    {
        \Stripe\Stripe::setApiKey('sk_test_WnJcOeq6NrJ5ZA7u8JahXLLO');
        // Get the token from the JS script
        $token = $request->stripeToken;
        // user info
        $amount = $request->amount;
        $name = $request->name;
        $lastName = $request->lastName;
        $email = $request->email;
        // Create a Customer
        $customer = \Stripe\Customer::create(array(
            "email" => $email,
            "source" => $token,
            'metadata' => array("name" => $name, "last_name" => $lastName)
        ));

        $charge = \Stripe\Charge::create(array(
            "amount" => $amount*100,
            "currency" => "NOK",
            "customer" => $customer->id,
            'metadata' => array("name" => $name, "last_name" => $lastName)
        ));

        return $charge;
    }
    
    public function additionalPayment(Request $request)
    {
        \Stripe\Stripe::setApiKey('sk_test_WnJcOeq6NrJ5ZA7u8JahXLLO');
        // Get the token from the JS script
        $token = $request->stripeToken;
        // user info
        $user = JWTAuth::authenticate();
        $amount = $request->amount;

        // Create a Customer
        // $customer = \Stripe\Customer::create(array(
        //     "email" => $user->email,
        //     "source" => $token,
        //     'metadata' => array("name" => $user->first_name, "last_name" => $user->last_name)
        // ));

        $charge = \Stripe\Charge::create(array(
            "amount" => $amount*100,
            "currency" => "NOK",
            "source" => "tok_amex",
            // "customer" => $customer->id,
            'metadata' => array("name" => $user->first_name, "last_name" => $user->last_name)
        ));

        if($charge){
            if(!$user->leagues()->where('league_id',$request->league)->exists()){
                $squad = new Squad;
                $squad->user_id = $user->id;
                $squad->league_id = $request->league;
                $squad->save();
                $user->leagues()->attach($user,['money' => 100000 ,'points' => 0,'league_id'=>$request->league ,'squad_id'=> $squad->id]);
            }
        }

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

        return $this->json($league_ids);

    }
}
