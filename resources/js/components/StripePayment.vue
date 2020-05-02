<template>
  <div >
    <div class='stripe-containter my-3 mx-3'>
      <div ref='card' ></div>
    </div>
    <b-button block class='my-1 ' :disabled='intentStatus' v-on:click='purchase'>
      <b-spinner v-if='intentStatus' variant='light' label='Loading...'></b-spinner>
      <span v-else>Pay Now</span>
    </b-button>
    <h4 v-if='success'>Processing...</h4>
  </div>
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
    fontFamily: '"Montserrat", Helvetica, sans-serif',
    fontSize: "18px",
    borderStyle: "solid",
    padding:'5px',
    borderWidth: "1px",
    "::placeholder": {
      color: "#aab7c4"
    }
  },
  invalid: {
    color: "#ef2d56",
    iconColor: "#ef2d56"
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
    app.card = elements.create("card",{style: style});
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
            app.success=true;
            app.$router.push('/invoices')
            //   document.location.href = "{{URL::to('success')}}";
            //document.location.href ="/success"
          }
        });
    }
  }
};
</script>