<?php

namespace App\Http\Controllers;
use Stripe\Stripe;
use Auth;
use Illuminate\Http\Request;

class StripePaymentController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user= Auth::user();
        //Set the api key
        Stripe::setApiKey('sk_test_6GCDXqiEEOn52aO7XcYEX7Bk00lJswlGE1');
        $intent = \Stripe\PaymentIntent::create([
            'amount' => 69420,
            'currency' => 'eur',
        ]); 
        return view('stripe.index', ['intent'=>$intent,'user'=>$user]);
    }

    public function success(){
        return view('stripe.success');
    }
}
