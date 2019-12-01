<?php

namespace App\Http\Controllers;
use Stripe\Stripe;
use Auth;
use App\Invoice;
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

    public function paySingleInvoice($id){
        $user = Auth::user();
        $invoice = Invoice::findOrFail($id);
        //Set the api key
        Stripe::setApiKey('sk_test_6GCDXqiEEOn52aO7XcYEX7Bk00lJswlGE1');
        $intent = \Stripe\PaymentIntent::create([
            'amount' => $invoice->total_cost,
            'currency' => 'eur',
        ]); 

        return view('stripe.paySingleInvoice', ['intent'=>$intent,'user'=>$user, 'invoice' => $invoice]);
    }
}
