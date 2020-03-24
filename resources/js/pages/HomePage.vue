<template>
  <div>
    <b-container fluid class='home-bg morrocan-bg'>
      <b-row>
        <b-col fluid="md" order-md="2" md="8" lg="7" class="no-padding-right" >
            <div class="py-3 px-5 gradient-bg" >
              <h1 class="home-title break-size">
                InvoiceAmigo
              </h1>
              <h4 class="text-light">Invoices for freelance web developers.</h4>
              <h5 class="text-light">Log. Send. Get paid.</h5>
          </div>
        </b-col>
        <b-col fluid="md" class="p-4" md="4" offset-lg="1">
          <div class="homepage-image">
            <b-img src="/img/phoneMockOld.png"  fluid alt="phone" class="rounded mx-auto d-block homepage-image" ></b-img>
          </div>
          </b-col>
        
      </b-row>
        <svg class='editorial' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' viewBox='0 24 150 28 ' preserveAspectRatio='none'>
          <defs>
            <path id='gentle-wave' d='M-160 44c30 0 
          58-18 88-18s
          58 18 88 18 
          58-18 88-18 
          58 18 88 18
          v44h-352z' />
          </defs>
          <g class='parallax1'>
            <use xlink:href='#gentle-wave' x='50' y='3' fill='#342e37' />
          </g>
          <g class='parallax2'>
            <use xlink:href='#gentle-wave' x='50' y='0' fill='#908296' />
          </g>
          <g class='parallax3'>
            <use xlink:href='#gentle-wave' x='50' y='9' fill='#423a45' />
          </g>
          <g class='parallax4'>
            <use xlink:href='#gentle-wave' x='50' y='6' fill='#f9f8f9' />
          </g>
        </svg>
    </b-container>
    <b-container class="my-5">
      <b-row class="my-5">
        <b-col md="5" class="py-3 px-4">
          <h1 >Invoicing for Freelancers</h1>
          <h2>Invoice and get paid online</h2>
          <p>Login or register today to start invoicing and start getting paid.</p>
        </b-col>
        <b-col class="purple-bg cutout cut1 d-table" >
          <div class="text-center d-table-cell align-middle" @click="$router.push(`/login`)">
         
            <h1  class="text-light box-text">Get Started</h1>
         
            <!-- <h1 class="text-light box-text">Get Started</h1> -->
          </div>
        </b-col>
      </b-row>
      <b-row class="my-5">
        <b-col md="5" order-md="2" class="py-3 px-4">
          <h1>Integrated Hosting Payment</h1>
          <h4>Sick of chasing down hosting payment?</h4>
          <p>Collect cPanel hosting payment directly and invoice, all in the one place</p>
        </b-col>
        <b-col  class="purple-bg cutout cut2 d-table">
         <div class="text-center d-table-cell align-middle" @click="$router.push(`/login`)">
            <span class=""><h1 class="text-light box-text">Connect to cPanel</h1></span>
          </div>
        </b-col>
         
      </b-row>
    </b-container>
  </div>
</template>

<script>
import Vue from "vue";
import { mapGetters, mapState } from "vuex";
import { CardPlugin, ButtonPlugin, LayoutPlugin } from "bootstrap-vue";
import ErrorPage from "../components/ErrorPage";
Vue.use(CardPlugin);
Vue.use(ButtonPlugin);
Vue.use(LayoutPlugin);

export default {
  components: {
    ErrorPage
  },
  computed: {
    ...mapGetters(["getProfile", "isAuthenticated", "isProfileLoaded"]),
    ...mapState({
      authLoading: state => state.auth.status === "loading",
      name: state => `${state.user.profile.name}`
    })
  }
};
</script>
