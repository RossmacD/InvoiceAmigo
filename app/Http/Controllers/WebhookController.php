<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe;
use App\Events\NotificationEvent;

use App\User;
use App\Business;
use App\Role;
use App\Invoice;
use Validator;
use App\InvoiceItems;
use App\Product;
use App\Service;



class WebhookController extends Controller
{
    public function webhooks(Request $request)
    {
        // // Stripe::setApiKey('sk_test_6GCDXqiEEOn52aO7XcYEX7Bk00lJswlGE1');
        // $signature = $request->header('Stripe-Signature');
        $payload = $request->getContent();
        $event = null;
        $data = json_decode($payload, true);
        try {
            $event = Stripe\Event::constructFrom($data);
        } catch (\UnexpectedValueException $e) {
            // Invalid payload
            // return  Response('Webhook Fucked', 400);
             return  response()->json(['message' => json_encode($e)],400);
        }

        // Handle the event
        switch ($event->type) {
            case 'payment_intent.succeeded':
                $paymentIntent = $event->data->object; // contains a \Stripe\PaymentIntent
                $seller=User::findOrFail($paymentIntent->metadata->seller_id);
                $invoice = Invoice::findOrFail($paymentIntent->metadata->invoice_id);
                // error_log($paymentIntent->metadata->invoice);
                event(new NotificationEvent('Invoice Payment Successful', $paymentIntent->metadata->customer_id),"/invoices",true);
                event(new NotificationEvent('Invoice #'.$invoice->invoice_number.' paid by ' . $seller->email, $paymentIntent->metadata->seller_id,"/invoice"."/".$invoice->id));
                $invoice->status = 'paid';
                $invoice->save();
                break;
                // ... handle other event types
            default:
                // Unexpected event type
        return  response()->json(['message' => "Unexpected Event",400]);
        }
        return  response()->json(['message' => "Webhook handled"]);
    }
}
