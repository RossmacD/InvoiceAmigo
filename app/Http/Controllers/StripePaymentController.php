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

    public function webhooks($_payload){
        //return view('stripe.webhooks');
        //$payload = @file_get_contents('php://input');
        $payload=$_payload;
        $event = null;

        try {
            $event = Stripe\Stripe\Event::constructFrom(
                json_decode($payload, true)
            );
        } catch (\UnexpectedValueException $e) {
            // Invalid payload
            return Response::make("", 400);
        }

        // Handle the event
        switch ($event->type) {
            case 'payment_intent.succeeded':
                $paymentIntent = $event->data->object; // contains a \Stripe\PaymentIntent
                handlePaymentIntentSucceeded($paymentIntent);
                break;
            case 'payment_method.attached':
                $paymentMethod = $event->data->object; // contains a \Stripe\PaymentMethod
                handlePaymentMethodAttached($paymentMethod);
                break;
                // ... handle other event types
            default:
                // Unexpected event type
                return Response::make("", 400);
        }

     return response(null, 200);

    }
}
