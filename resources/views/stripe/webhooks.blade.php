<?
$payload = @file_get_contents('php://input');
$event = null;

try {
$event = \Stripe\Event::constructFrom(
json_decode($payload, true)
);
} catch(\UnexpectedValueException $e) {
// Invalid payload
http_response_code(400);
exit();
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
http_response_code(400);
exit();
}

http_response_code(200);