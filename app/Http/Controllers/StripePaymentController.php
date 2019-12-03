<?php

namespace App\Http\Controllers;
use Stripe\Stripe;
use Auth;
use App\User;
use App\Invoice;
use App\InvoiceItems;
use App\Product;
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
        $invoiceItems = InvoiceItems::where('invoice_id', $id)->get();
        $client =  User::where('id', $invoice->user_id)->firstOrFail();
        //dd($invoice);
        //Set the api key
        Stripe::setApiKey('sk_test_6GCDXqiEEOn52aO7XcYEX7Bk00lJswlGE1');
        $intent = \Stripe\PaymentIntent::create([
            'amount' => $invoice->total_cost,
            'currency' => 'eur',
        ]); 

        return view('stripe.paySingleInvoice', ['intent'=>$intent,'user'=>$user, 'invoice' => $invoice, 'invoiceItems' => $invoiceItems,'client' => $client]);
    }

    public function webhooks(Request $request){
        $payload = $request->getContent();
        $event = null;
        try {
            $event = Stripe\Event::constructFrom(
                json_decode($payload, true)
            );
        } catch (\UnexpectedValueException $e) {
            // Invalid payload
            return new Response('Webhook Fucked', 400);
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
                return new Response('Webhook Fucked', 400);
        }
        return new Response('Webhook Handled', 200);
    }
}
