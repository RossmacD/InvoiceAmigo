<template>
  <div>
    <LoadingPage v-if='!loaded'></LoadingPage>
    <div v-else>
      <h3>Invoice #{{invoice.invoice_number}}</h3>
      <h5>Sent: {{invoice.invoice_date}}</h5>
      <h5>Due: {{invoice.due_date}}</h5>
      <hr />
      <h5>Invoice Items</h5>
      <b-table responsive='md' striped hover :fields='fields' :items='invoice.invoiceLines' foot-clone foot-variant='light'>
        <template v-slot:cell(no.)='data'>
          <span>{{ data.index + 1 }}</span>
        </template>

        <template v-slot:cell(cost)='data'>
          <span>{{curPrefix + invoice.invoiceLines[data.index].cost}} {{ invoice.invoiceLines[data.index].rate_unit?invoice.invoiceLines[data.index].rate_unit+"ly":'' }}</span>
        </template>

        <template v-slot:cell(sub_total)='data'>
          <span>{{curPrefix + invoice.invoiceLines[data.index].sub_total }}</span>
        </template>

        <template v-slot:foot(sub_total)>Total Cost: €{{invoice.total_cost}}</template>
        <template v-slot:foot()>
          <br />
        </template>
      </b-table>
      <h5>Notes:</h5>
      <p>{{invoice.notes}}</p>
      <b-badge pill class="mx-3 px-1" variant='success' v-if="invoice.status==`paid`"><h4 >Invoice Paid</h4></b-badge>
      <b-badge pill variant='danger' v-else><h4 >Invoice Unpaid</h4></b-badge>
      <StripePayment v-if="invoice.user_id==id&&invoice.status!=`paid`" :id="invoice.id"/>
      
    </div>
  </div>
</template>

<script>
import Vue from "vue";
import axios from "axios";
import { ButtonPlugin, SpinnerPlugin, TablePlugin,BadgePlugin } from "bootstrap-vue";
import { mapGetters, mapState } from "vuex";
import InvoiceCreate from "./InvoiceCreate";
import LoadingPage from "../../components/LoadingPage";
import StripePayment from "../../components/StripePayment";
Vue.use(ButtonPlugin);
Vue.use(SpinnerPlugin);
Vue.use(TablePlugin);
Vue.use(BadgePlugin);
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
        "no.",
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