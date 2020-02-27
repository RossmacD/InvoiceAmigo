<template>
  <b-row>
    <b-col>
      <div ref='card'></div>
    </b-col>
    <b-col>
      <b-button :disabled="intentStatus" v-on:click='purchase'><b-spinner v-if="intentStatus" variant='light' label='Loading...'></b-spinner><span v-else>Pay Now</span></b-button>
      <h1 v-if="success">Payment processing!!!</h1>
    </b-col>
  </b-row>
</template>

<script>
import Vue from "vue";
import axios from "axios";
import { ButtonPlugin, SpinnerPlugin, LayoutPlugin } from "bootstrap-vue";
Vue.use(ButtonPlugin);
Vue.use(SpinnerPlugin);
Vue.use(LayoutPlugin);

//Initialise Stripe elemnts
const stripe = Stripe(process.env.MIX_STRIPE_KEY);
let elements = stripe.elements();
let card;
let intent;
var style = {
  base: {
    color: "#32325d",
    fontSmoothing: "antialiased",
    fontSize: "16px",
    borderStyle: "solid",
    borderWidth: "1px",
    "::placeholder": {
      color: "#aab7c4"
    }
  },
  invalid: {
    color: "#fa755a",
    iconColor: "#fa755a"
  }
};

export default {
  data() {
    return {
        intentStatus:true,
        success:false
    };
  },
  mounted: function() {
    const app = this;
    axios
      .get("/api/pay/" + this.$route.params.id)
      .then(response => {
        console.log(response);
        app.intent=response.data.intent.client_secret;
        app.intentStatus=false;
      })
      .catch(err => console.log(err));
    app.card = elements.create("card");
    app.card.mount(app.$refs.card);
    //Add a listener to validate card
    // card.addEventListener("change", function(event) {
    //   if (event.error) {
    //     displayError.textContent = event.error.message;
    //   } else {
    //     displayError.textContent = "";
    //   }
    // });
  },
  methods: {
    purchase: function() {
        const app=this;
        app.intentStatus=true;

      stripe
        .confirmCardPayment(app.intent, {
          payment_method: {
            card: app.card,
            billing_details: {
              name: "Hardcoded Name",
              email: "hardemail@email.com"
            }
          }
        })
        .then(function(result) {
          if (result.error) {
            // Display error.message in your UI.
            console.log(result.error.message);
        app.intentStatus=true;
          } else {
            // The payment has succeeded. Display a success message.
            console.log("Success");
            app.success=true;
            //   document.location.href = "{{URL::to('success')}}";
            //document.location.href ="/success"
          }
        });
    }
  }
};
</script>