<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe;
use App\Events\NotificationEvent;

use App\User;
use App\Business;
use App\Role;
use App\Transactions;
use App\Invoice;
use Validator;
use App\InvoiceItems;
use App\Product;
use App\Service;



class WebhookController extends Controller
{
    public function webhooks(Request $request)
    {
        // $signature = $request->header('Stripe-Signature');
        $payload = $request->getContent();
        $event = null;
        $data = json_decode($payload, true);
        try {
            $event = Stripe\Event::constructFrom($data);
        } catch (\UnexpectedValueException $e) {
            // Invalid payload
             return  response()->json(['message' => json_encode($e)],400);
        }

        // Handle the event
        switch ($event->type) {
            case 'payment_intent.succeeded':
                //Get payment intent
                $paymentIntent = $event->data->object; 
                
                //Get objects based on metadata
                $seller=User::findOrFail($paymentIntent->metadata->seller_id);
                $invoice = Invoice::findOrFail($paymentIntent->metadata->invoice_id);
               
                //Send event to pusher
                event(new NotificationEvent('Invoice Payment Successful', $paymentIntent->metadata->customer_id),"/invoices",true);
                event(new NotificationEvent('Invoice #'.$invoice->invoice_number.' paid by ' . $seller->email, $paymentIntent->metadata->seller_id,"/invoice"."/".$invoice->id));

                //Update invoice status
                $invoice->status = 'paid';
                $invoice->save();
                
                //Log transaction to DB
                $transaction=new Transactions;
                $transaction->invoice_id= $invoice->id;
                $transaction->cur = "eur";
                $transaction->stripe_payment_id= $paymentIntent->id;
                $transaction->amount=$invoice->total_cost;
                $transaction->save();
                break;
            default:
                // Unexpected event type
                return  response()->json(['message' => "Unexpected Event",400]);
        }
        return  response()->json(['message' => "Webhook handled"]);
    }
}
