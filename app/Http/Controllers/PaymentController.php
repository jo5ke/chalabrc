<?php

namespace App\Http\Controllers;
use Stripe;

use Illuminate\Http\Request;

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
            "source" => "tok_amex",
            'metadata' => array("name" => $name, "last_name" => $lastName)
        ));

        $charge = \Stripe\Charge::create(array(
            "amount" => $amount*100,
            "currency" => "NOK",
            "source" => $token, // obtained with Stripe.js
            'metadata' => array("name" => $name, "last_name" => $lastName)
        ));

        return $charge;
    }
}
