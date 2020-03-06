<template>
  <div>
    <h2 class='mb-4'>
      Your Invoices
      <b-button v-if='isBusiness' to='/invoices/create' class='float-right'>+ New</b-button>
    </h2>
    <!-- <b-button-group>
      <b-button>Sent</b-button>
      <b-button>Received</b-button>
    </b-button-group>-->

    <b-tabs v-if='isBusiness' pills>
      <b-tab title='Sent' active>
        <LoadingPage v-if='!outgoingInvoices'></LoadingPage>
        <EmptyIndex indexType='invoice' v-else-if='outgoingInvoices.length===0'></EmptyIndex>
        <b-card v-else v-for='(invoice,index) in outgoingInvoices' v-bind:key='invoice.id' class='my-2' footer-bg-variant='light' :footer='invoice.created_at' header header-bg-variant='dark'>
          <template v-slot:header>
            <b-row>
              <b-col>
                <h3 class='text-light'>#{{ invoice.invoice_number }}:</h3>
              </b-col>
              <b-col>
                <b-button class='float-right mx-1' variant='secondary' :pressed='false' :to='`invoices/edit/`+invoice.id' v-if='invoice.status==`draft`' size='sm'>
                  <b-icon variant='light' icon='pen' style='width: 20px; height: 20px'></b-icon>
                </b-button>
                <DeleteButton class='float-right mx-1' v-on:on-confirm='deleteInvoice' :id='invoice.id' :index='index' v-if='invoice.status==`draft`'></DeleteButton>
                <ReversalButton class='float-right mx-1' v-on:on-confirm='reverseInvoice' :id='invoice.id' :index='index' v-else></ReversalButton>
              </b-col>
            </b-row>
          </template>
          <b-row>
            <b-col>{{invoice.note}}</b-col>
            <b-col>
              <b-button class='float-right mx-1' variant='secondary' :pressed='false' :to=''invoices/'+invoice.id' size='sm'>
                <b-icon variant='light' icon='eye-fill' style='width: 20px; height: 20px'></b-icon>
              </b-button>
            </b-col>
          </b-row>
        </b-card>
      </b-tab>

      <b-tab title='Received'>
        <LoadingPage v-if='!incomingInvoices'></LoadingPage>
        <EmptyIndex :button='false' indexType='invoice' v-else-if='incomingInvoices.length===0'></EmptyIndex>
        <b-card v-else v-for='(invoice,index) in incomingInvoices' v-bind:key='invoice.id' class='my-2' footer-bg-variant='light' :footer='invoice.created_at' header header-bg-variant='dark'>
          <template v-slot:header>
            <b-row>
              <b-col>
                <h3 class='text-light'>#{{ invoice.invoice_number }}:</h3>
              </b-col>
            </b-row>
          </template>
          <b-row>
            <b-col>{{invoice.note}}</b-col>
            <b-col>
              <b-button class='float-right mx-1' variant='secondary' :pressed='false' :to='`invoices/`+invoice.id' size='sm'>
                <b-icon variant='light' icon='eye-fill' style='width: 20px; height: 20px'></b-icon>
              </b-button>
            </b-col>
          </b-row>
        </b-card>
      </b-tab>
    </b-tabs>

    <div v-else>
      <LoadingPage v-if='!incomingInvoices'></LoadingPage>
      <EmptyIndex :button='false' indexType='invoice' v-else-if='incomingInvoices.length===0'></EmptyIndex>
      <b-card v-else v-for='(invoice,index) in incomingInvoices' v-bind:key='invoice.id' class='my-2' footer-bg-variant='light' :footer='invoice.created_at' header header-bg-variant='dark'>
        <template v-slot:header>
          <b-row>
            <b-col>
              <h3 class='text-light'>#{{ invoice.invoice_number }}:</h3>
            </b-col>
          </b-row>
        </template>
        <b-row>
          <b-col>{{invoice.note}}</b-col>
          <b-col>
            <b-button class='float-right mx-1' variant='secondary' :pressed='false' :to=''invoices/'+invoice.id' size='sm'>
              <b-icon variant='light' icon='eye-fill' style='width: 20px; height: 20px'></b-icon>
            </b-button>
          </b-col>
        </b-row>
      </b-card>
    </div>
  </div>
</template>


<script>
import axios from "axios";
import Vue from "vue";
import { mapGetters, mapState } from "vuex";
import { SpinnerPlugin, ButtonPlugin, CardPlugin } from "bootstrap-vue";
import EmptyIndex from "../../components/EmptyIndex";
import LoadingPage from "../../components/LoadingPage";
import ErrorPage from "../../components/ErrorPage";
import DeleteButton from "../../components/DeleteButton";
import ReversalButton from "../../components/ReversalButton";
Vue.use(SpinnerPlugin);
Vue.use(ButtonPlugin);
Vue.use(CardPlugin);
export default {
  name: "InvoiceIndex",
  components: {
    EmptyIndex,
    LoadingPage,
    ErrorPage,
    DeleteButton,
    ReversalButton
  },
  data() {
    return {
      incomingInvoices: null,
      outgoingInvoices: null
    };
  },
  mounted() {
    const app = this;
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
      axios
        .delete("/api/invoices/" + id)
        .then(function(response) {
          app.$delete(app.invoices, index);
        })
        .catch(function(error) {
          console.log(error);
        });
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
    }
  },
  computed: {
    ...mapGetters(["isAuthenticated", "isBusiness"]),
    ...mapState({})
  }
};
</script>