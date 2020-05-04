<template>
  <div>
    <b-row>
      <b-col md='12'>
        <b-form-group label='Account Holder Full Name' label-for='account_name'>
          <b-form-input id='account_name' type='text' name='account_name' required autocomplete='account_name' autofocus v-model='account_name' placeholder='Enter account holder full name'></b-form-input>
          <b-form-invalid-feedback id='input-live-feedback'>Enter at least 5 letters</b-form-invalid-feedback>
        </b-form-group>
      </b-col>
    </b-row>

    <b-row>
      <b-col md='12'>
        <b-form-group>
          <label for='ibanElement'>Your IBAN</label>
          <div ref='iban'>
            <!-- A Stripe Element will be inserted here. -->
          </div>
        </b-form-group>
      </b-col>
    </b-row>
    <b-row>
      <b-col>
        <b-form-text id='mandate-acceptance'>By providing your IBAN, you are authorizing Stripe, our payment service provider, to store yout IBAN for future payouts.</b-form-text>
      </b-col>
    </b-row>
    <br/>
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
let iban;

export default {
  name: "IbanCollect",
  data() {
    return {
      account_name: "",
      success: false
    };
  },
  mounted: function() {
    const app = this;
    app.iban = elements.create("iban", {
      supportedCountries: ["SEPA"],
      placeholderCountry: "IE"
    });
    app.iban.mount(app.$refs.iban);
  },
  methods: {}
};
</script>