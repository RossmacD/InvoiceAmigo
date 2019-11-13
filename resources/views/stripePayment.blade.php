@extends('layouts.app')

@section('headScripts')

<?php 
use Stripe\Stripe;
//Set the api key
Stripe::setApiKey('sk_test_6GCDXqiEEOn52aO7XcYEX7Bk00lJswlGE1');
$intent = \Stripe\PaymentIntent::create([
    'amount' => 42069,
    'currency' => 'eur',
]);   
 
?>
@endsection

@section('content')
<div class="card text-center">
    <div class="card-header">
      <ul class="nav nav-tabs card-header-tabs">
        <li class="nav-item">
          <a class="nav-link active" href="#">Pay by Card</a>
        </li>
      </ul>
    </div>
    <div class="card-body">
      <h5 class="card-title">Cost: <?= $intent->amount?></h5>
      <p class="card-text"></p>
      <div class="w-50 mx-auto">
      <label for="card-element">
          Credit or debit card
          </label>
          {{-- <input id="cardholder-name" type="text"> --}}
           <div id="card-element">
               <!-- Elements will create input elements here -->
           </div>
           <!-- We'll put the error messages in this element -->
           <div id="card-errors" role="alert"></div>
           <button id="card-button">Pay Now</button>
      </div>
    </div>

         
          
      
  @endsection

  @section('scripts')
  <script>
    //Initialise Stripe elemnts
      var stripe = Stripe('{{ config("services.stripe.stripe_key") }}');
      var elements = stripe.elements();
      var card = elements.create("card");
      var cardButton = document.getElementById('card-button');
      var cardElement=document.getElementById("card-element");
      card.mount(cardElement);
      

      //Add a listener to validate card
      card.addEventListener('change', function(event) {
        var displayError = document.getElementById('card-errors');
        if (event.error) {
          displayError.textContent = event.error.message;
        } else {
          displayError.textContent = '';
        }
    });

      var clientSecret='<?= $intent->client_secret?>';
      //Submit Clients
      cardButton.addEventListener('click', function(ev) {
        stripe.confirmCardPayment(clientSecret, {
          payment_method: {
            card: card,
            billing_details: {
              name: 'Jenny Rosen'
            }
          }
        }).then(function(result) {
          if (result.error) {
            // Display error.message in your UI.
            console.log(result.error.message);
          } else {
            // The payment has succeeded. Display a success message.
            console.log("Hell yeah");
          }
        });
      });



      //SHIT
    // // Set up Stripe.js and Elements to use in checkout form
    //   var style = {
    //     base: {
    //       color: "#32325d",
    //     }
    //   };

    //   //Create card form and mount element
    //   //var card = elements.create("card", { style: style });
      
      
      

    
  </script>
 @endsection