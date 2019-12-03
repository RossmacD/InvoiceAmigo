@extends('layouts.app')

@section('content')
<style>
    /**
    * The CSS shown here will not be introduced in the Quickstart guide, but shows
    * how you can use CSS to style your Element's container.
    */
    .StripeElement {
        margin-top: 15px;
        margin-bottom: 15px;
        padding: 10px 12px;
        border: 1px solid transparent;
        border-radius: 5px;
        background-color: white;

        box-shadow: 0 1px 3px 0 #e6ebf1;
        -webkit-transition: box-shadow 150ms ease;
        transition: box-shadow 150ms ease;
    }

    .StripeElement--focus {
        box-shadow: 0 1px 3px 0 #cfd7df;
    }

    .StripeElement--invalid {
        border-color: #fa755a;
    }

    .StripeElement--webkit-autofill {
        background-color: #fefde5 !important;
    }
</style>

<h1>Your billing Info:</h1>
<p>Name: {{$user->name}}</p>
<p>Email: {{$user->email}}</p>
<hr>
<h2>Invoice From:</h2>
<p>Name: {{$client->name}}</p>
<p>Email: {{$client->email}}</p>
<hr>
<h1>Invoice #{{$invoice->invoice_number}}</h1>
<h4>Due date:</h4>
<p>{{$invoice->due_date}}</p>
<h2>Products</h2>
<div class="row">
    <div class="col-md-12">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th scope="col">Num</th>
                    <th scope="col">Name</th>
                    <th scope="col">Description</th>
                    <th scope="col">Quantity</th>
                    <th scope="col">Cost</th>

                </tr>
            </thead>
            <tbody id="myAddedRows">
                @foreach ($invoiceItems as $invoiceItem)

                <tr id="invoiceItem{{$loop->index}}">
                    <th scope="row">{{$loop->index+1}}</th>
                    <td>
                        {{$invoiceItem->product_name}}

                    </td>
                    <td>
                        {{$invoiceItem->product_description}}

                    </td>
                    <td>
                        {{$invoiceItem->product_quantity}}

                    </td>
                    <td>
                        {{$invoiceItem->product_cost}}

                    </td>
                    @endforeach

            </tbody>
        </table>
        <hr>
        
@if($invoice->note!==null)
<h5>Notes:</h5>
<p>{{$invoice->note}}</p>
@endif
<div class="card ">
    <div class="card-header">
        <ul class="nav nav-tabs card-header-tabs">
            <li class="nav-item">
                <a class="nav-link active" href="#">Pay by Card</a>
            </li>
        </ul>
    </div>
    <div class="card-body">

        <h5 class="card-title">Cost: €<?= number_format((float)$intent->amount, 2, '.', '')?></h5>
        <p class="card-text"></p>
        <div class="w-100">
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
            var style = {
            base: {
                color: '#32325d',
                fontSmoothing: 'antialiased',
                fontSize: '16px',
                borderStyle: 'solid',
                borderWidth: '1px',
                '::placeholder': {
                color: '#aab7c4'
                }
            },
            invalid: {
                color: '#fa755a',
                iconColor: '#fa755a'
            }
            };
            var card = elements.create("card", { style: style });
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
                    name: '{{$user->name}}',
                    email: '{{$user->email}}'
                }
                }
            }).then(function(result) {
                if (result.error) {
                // Display error.message in your UI.
                console.log(result.error.message);
                } else {
                // The payment has succeeded. Display a success message.
                console.log('Success');
                document.location.href = "{{URL::to('success')}}";
                //document.location.href ="/success"
                }
            });
            });
    </script>
    @endsection