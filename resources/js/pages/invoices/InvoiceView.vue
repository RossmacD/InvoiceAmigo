<template>
 <div class='invoice-fillscreen py-2' >
    <!-- <b-container class="light-bg px-2 pt-2 pb-2 radius15 mb-1">
      <h2 class='centerVert'>Invoice from ultan@gmail.com</h2>
    </b-container> -->
    <b-container class='light-bg radius15 min-vh-80 p-5' >
    <LoadingPage v-if='!loaded'></LoadingPage>
    <div v-else>
       <!-- <b-badge pill class="mx-3 px-1 py-1" variant='success' v-if="invoice.status==`paid`"><h6 >Invoice Paid</h6></b-badge> -->
       <b-row class="my-2">
         <b-col>
           <h1 class='display-3'>INVOICE</h1>
          <div class="mx-1">
            <small>Invoice Date:</small>
              <p>{{invoice.invoice_date}}</p>
            <small>Issue Date:</small>
              <p>{{invoice.due_date}}</p>
          </div>
        </b-col>
         <b-col class="text-right">
          <h3>Invoice #{{invoice.invoice_number}}</h3>
           <h3 >{{invoice.business.business_name}}</h3>
           <p>{{invoice.business.address}}</p>
           <p>{{invoice.business.country}}</p>
           <p>{{invoice.business.postcode}}</p>
         </b-col>
       </b-row>
      <!-- <hr /> -->
      <h5>Invoice Items</h5>
      <b-table responsive='md' striped hover :fields='fields' :items='invoice.invoiceLines' foot-clone foot-variant='light'>
       
        <template v-slot:cell(cost)='data'>
          <span>{{curPrefix + invoice.invoiceLines[data.index].cost}} {{ 
              invoice.invoiceLines[data.index].rate_unit&&invoice.invoiceLines[data.index].rate_unit!=="day"?invoice.invoiceLines[data.index].rate_unit+"ly":
              invoice.invoiceLines[data.index].rate_unit==="day"?'Daily':'' 
            }}</span>
        </template>

        <template v-slot:cell(sub_total)='data'>
          <span>{{curPrefix + invoice.invoiceLines[data.index].sub_total }}</span>
        </template>

        <template v-slot:foot(sub_total)>Total Cost: €{{invoice.total_cost}}</template>
        <template v-slot:foot()>
          <br />
        </template>
      </b-table>
      <div v-if="invoice.notes">
        <h5>Notes:</h5>
        <p>{{invoice.notes}}</p>
      </div>
     
    </div> 
    </b-container>
    
     <b-container class="light-bg px-2 pt-2 pb-0 radius15 mb-1 mt-4" v-if="invoice.user_id==id&&invoice.status!=`paid`">
       <b-row >
          <b-col sm='4' class="px-4 text-center" >
              <h6 class="p-2 custBadge mb-2 text-light">Invoice Unpaid</h6>
          </b-col>
          <b-col class="px-4 text-center">
            <h5 class="p-2 text-right align-middle mb-2 px-3">Total Cost: €{{invoice.total_cost}} </h5>
          </b-col>
      </b-row>
      <b-row class="px-1" >
        <b-col class="med-bg radius15">
          <StripePayment class='my-2' :id="invoice.id"/>
        </b-col>
      </b-row>
    </b-container>
  </div>
</template>

<script>
import Vue from "vue";
import axios from "axios";
import { ButtonPlugin, SpinnerPlugin, TablePlugin,BadgePlugin,LayoutPlugin } from "bootstrap-vue";
import { mapGetters, mapState } from "vuex";
import InvoiceCreate from "./InvoiceCreate";
import LoadingPage from "../../components/LoadingPage";
import StripePayment from "../../components/StripePayment";
Vue.use(ButtonPlugin);
Vue.use(SpinnerPlugin);
Vue.use(TablePlugin);
Vue.use(BadgePlugin);
Vue.use(LayoutPlugin);
export default {
  name: "InvoiceView",
  components: {
    LoadingPage,
    StripePayment
  },
  data() {
    return {
      curPrefix: "€",
      invoice: {},
      fields: [
        "name",
        "description",
        { key: "cost", label: "Cost", variant: "active" },
        { key: "quantity", label: "Quantity", variant: "active" },
        { key: "sub_total", label: "Sub Total", variant: "active" }
      ],
      loaded: "",
      intent:""
    };
  },
  methods: {},
  computed: {
    ...mapGetters(["isAuthenticated","getProfile"]),
    ...mapState({
      id: state => `${state.user.profile.id}`,
    })
  },
  mounted() {
    const app = this;
    if (app.isAuthenticated) {
      axios
        .get("/api/invoices/" + this.$route.params.id)
        .then(response => {
          app.invoice = response.data.invoice;
          app.loaded = true;
        })
        .catch(err => {
          console.log(err);
        });
    }
  }
};
</script>