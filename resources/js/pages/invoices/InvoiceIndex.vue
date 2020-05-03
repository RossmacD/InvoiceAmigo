<template>
  <div>
    <h2 class='mb-4'>
      Your Invoices
      <b-button v-if='isBusiness' to='/invoices/create' class='float-right'>+ New</b-button>
    </h2>
    <div v-if='isBusiness'>
      <b-tabs pills v-model='tabView' content-class="bg-shaded pt-3">
        <!--Filters -->
        <template v-slot:tabs-end>
          <b-button-toolbar aria-label='Filters' style='align-self:center;margin-left:auto'>
            <b-button-group size='sm' >
              <b-button disabled variant="light"><b-icon icon="funnel-fill"></b-icon></b-button>
              <b-button :variant='filter===`any`?`primary`:`light`' @click="switchFilter(`any`)">All</b-button>
              <b-button v-if='isBusiness&&tabView===0' :variant='filter===`draft`?`primary`:`light`' @click="switchFilter(`draft`)">Draft</b-button>
              <b-button :variant='filter===`unpaid`?`primary`:`light`' @click="switchFilter(`unpaid`)">Unpaid</b-button>
              <b-button :variant='filter===`paid`?`primary`:`light`' @click="switchFilter(`paid`)">Paid</b-button>
            </b-button-group>
          </b-button-toolbar>
        </template>
        <!-- Tabs -->
        <b-tab title='Sent' active variant='light'>
          <InvoiceIndexTab :invoiceList='outgoingInvoices' v-on:on-delete-confirm='deleteInvoice' v-on:on-reverse-confirm='reverseInvoice' :recieving='false' :filter='filter'></InvoiceIndexTab>
        </b-tab>
        <b-tab title='Received'>
          <InvoiceIndexTab :invoiceList='incomingInvoices' v-on:on-delete-confirm='deleteInvoice' v-on:on-reverse-confirm='reverseInvoice' recieving :filter='filter'></InvoiceIndexTab>
        </b-tab>
      </b-tabs>
    </div>

    <div v-else>
      <small>Filter</small>
      <b-button-toolbar aria-label='Filters' style='align-self:center;margin-left:auto'>
            <b-button-group size='sm'>
              <b-button disabled variant="light"><b-icon icon="funnel-fill"></b-icon></b-button>
              <b-button :variant='filter===`any`?`primary`:`light`' @click="switchFilter(`any`)">All</b-button>
              <b-button v-if='isBusiness&&tabView===0' :variant='filter===`draft`?`primary`:`light`' @click="switchFilter(`draft`)">Draft</b-button>
              <b-button :variant='filter===`unpaid`?`primary`:`light`' @click="switchFilter(`unpaid`)">Unpaid</b-button>
              <b-button :variant='filter===`paid`?`primary`:`light`' @click="switchFilter(`paid`)">Paid</b-button>
            </b-button-group>
          </b-button-toolbar>
      <InvoiceIndexTab :invoiceList='incomingInvoices' v-on:on-delete-confirm='deleteInvoice' v-on:on-reverse-confirm='reverseInvoice' recieving :filter='filter'></InvoiceIndexTab>
    </div>
  </div>
</template>


<script>
import axios from "axios";
import Vue from "vue";
import { mapGetters, mapState } from "vuex";
import { SpinnerPlugin, ButtonPlugin, CardPlugin,BadgePlugin,BIcon } from "bootstrap-vue";
import EmptyIndex from "../../components/EmptyIndex";
import LoadingPage from "../../components/LoadingPage";
import ErrorPage from "../../components/ErrorPage";
import DeleteButton from "../../components/DeleteButton";
import ReversalButton from "../../components/ReversalButton";
import InvoiceIndexTab from "../../components/InvoiceIndexTab";
Vue.use(SpinnerPlugin);
Vue.use(ButtonPlugin);
Vue.use(CardPlugin);
Vue.use(BadgePlugin);
export default {
  name: "InvoiceIndex",
  components: {
    EmptyIndex,
    LoadingPage,
    ErrorPage,
    DeleteButton,
    ReversalButton,
    InvoiceIndexTab,
    BIcon
  },
  data() {
    return {
      incomingInvoices: null,
      outgoingInvoices: null,
      tabView:0,
      filter:'any',
    };
  },
  mounted() {
    const app =this;
    if (app.isAuthenticated) {
      axios
        .get("/api/invoices")
        .then(response => {
          app.incomingInvoices = response.data.incomingInvoices.data;
          if (app.isBusiness) {
            app.outgoingInvoices = response.data.outgoingInvoices.data;
          }
        })
        .catch(err => {
          console.log(err);
        });
    }
  },
  methods: {
    deleteInvoice(id, index) {
      const app = this;
      // console.log(app.isBusiness&&app.tabView===0)
      axios
        .delete("/api/invoices/" + id)
        .then(function(response) {
          app.isBusiness&&app.tabView===0?
          app.$delete(app.outgoingInvoices, index):
          app.$delete(app.incomingInvoices, index);
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    switchFilter(filter){
      this.filter=filter;
    },
    reverseInvoice(id, index) {
      const app = this;
      // app.outgoingInvoices[id].status = "reversed";
      
      axios
        .put(
          "/api/invoices/" + app.outgoingInvoices[id].id,
          app.outgoingInvoices[id]
        )
        .then(res => console.log(res.data))
        .catch(err => console.log(err.data));
    },
    handleResize () {
        const clientWidth = window.innerWidth
       console.log(clientWidth)
       this.windowWidth=clientWidth;
      }
  },
  computed: {
    ...mapGetters(["isAuthenticated", "isBusiness"]),
    ...mapState({})
  }
};
</script>

<style >
.bg-white{
  background-color: white;
}

* > .nav {
  background-color:rgb(218, 213, 220,0.15);
  /* background: rgb(255,255,255); */
  border-top: 1px solid rgba(39, 35, 42, 0.1);
  border-bottom: 1px solid rgba(39, 35, 42, 0.1);
  border-top-left-radius: 10px;
  border-top-right-radius: 10px;
  padding: 0.9rem 0.45rem;
}

</style>