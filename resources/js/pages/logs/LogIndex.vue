<template>
  <div>
    <h2 class='mb-4'>
      Your Logs
      <b-button to='/logs/create' class='float-right'>+ New</b-button>
    </h2>
    <LoadingPage v-if='!draftInvoices'></LoadingPage>
    <EmptyIndex :button='false' indexType='invoice' v-else-if='draftInvoices.length===0'></EmptyIndex>
    <b-row v-else>
      <b-col md='6' lg='4' v-for='invoice in draftInvoices' :key='invoice.id'>
        <b-card class='my-2' style='height: 300px;'>
          <h3>{{invoice.draft_email}}</h3>
          <b-list-group flush>
            <b-list-group-item v-for='item in invoice.invoice_items.slice(0, 3)' :key='item.id'>{{item.name}}</b-list-group-item>
          </b-list-group>
          <template v-slot:footer>
            <b-button  :to='`/logs/edit/${invoice.id}`' variant='success' class='float-right'>Add Time</b-button>
          </template>
        </b-card>
      </b-col>
      <b-col md='6' lg='4'>
        <b-card class='my-2 text-center' border-variant='dark' style='border-style: dashed!important;height: 300px;' footer-border-variant='dark'>
          <div class='align-middle'>
            <h3>New Log?</h3>Create a log for a new customer
          </div>
          <template v-slot:footer>
            <b-button to='/logs/create' variant='dark' style='border-style: dashed;' class='float-right'>New Log</b-button>
          </template>
        </b-card>
      </b-col>
    </b-row>
  </div>
</template>

<script>
import Vue from "vue";
import { mapGetters, mapState } from "vuex";
import EmptyIndex from "../../components/EmptyIndex";
import LoadingPage from "../../components/LoadingPage";
import ErrorPage from "../../components/ErrorPage";
import DeleteButton from "../../components/DeleteButton";
import {
  SpinnerPlugin,
  ButtonPlugin,
  LayoutPlugin,
  CardPlugin
} from "bootstrap-vue";
Vue.use(SpinnerPlugin);
Vue.use(ButtonPlugin);
Vue.use(LayoutPlugin);
Vue.use(CardPlugin);

export default {
  components: {
    EmptyIndex,
    LoadingPage,
    ErrorPage,
    DeleteButton,
  },
  data() {
    return {
      draftInvoices: null
    };
  },
  mounted() {
    const app = this;
    if (app.isAuthenticated) {
      axios
        .get("/api/logs")
        .then(response => {
          // console.log(response.data);
          app.draftInvoices = response.data.data;
          app.loaded=true
        })
        .catch(err => {
          console.log(err);
          app.hitError=true;
        });
    }
  },
  computed: {
    ...mapGetters(["isAuthenticated"]),
    ...mapState({}),
  }
};
</script>