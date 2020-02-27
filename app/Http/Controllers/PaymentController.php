<?php

namespace App\Http\Controllers;

use Stripe\Stripe;
use Auth;
use App\User;
use App\Invoice;
use App\InvoiceItems;
use App\Product;
use Illuminate\Http\Request;

class PaymentController extends Controller
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
    // public function index()
    // {
    //     $user = Auth::user();
    //     //Set the api key
    //     Stripe::setApiKey('sk_test_6GCDXqiEEOn52aO7XcYEX7Bk00lJswlGE1');
    //     $intent = \Stripe\PaymentIntent::create([
    //         'amount' => 69420,
    //         'currency' => 'eur',
    //     ]);
    //     return response()->json(['intent' => $intent], 200);
    // }

    public function paySingleInvoice($id)
    {
        // $user = Auth::user();
        $invoice = Invoice::findOrFail($id);
        // $invoiceItems = InvoiceItems::where('invoice_id', $id)->get();
        // $client =  User::where('id', $invoice->user_id)->firstOrFail();
        //dd($invoice);
        //Set the api key
        Stripe::setApiKey('sk_test_6GCDXqiEEOn52aO7XcYEX7Bk00lJswlGE1');
        $intent = \Stripe\PaymentIntent::create([
            'amount' => $invoice->total_cost,
            'currency' => 'eur',
        ]);

        return  response()->json([
            'intent' => $intent
        ], 200);
    }


}
