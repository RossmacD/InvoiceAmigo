<template>
  <div>
    <h2 class='mb-4'>
      Your Invoices
      <b-button to='/invoices/create' class='float-right'>+ New</b-button>
    </h2>
    <LoadingPage v-if='!invoices'></LoadingPage>
    <EmptyIndex indexType='invoice' v-else-if='invoices.length===0'></EmptyIndex>
    <b-card v-else v-for='(invoice,index) in invoices' v-bind:key='invoice.id' class='my-2' footer-bg-variant='light' :footer='invoice.created_at' header header-bg-variant="dark">
    <template v-slot:header>
          <b-row>
       <b-col>
            <h3 class="text-light">#{{ invoice.invoice_number }}: </h3>
          </b-col>
          <b-col>
            <b-button class='float-right mx-1' variant='secondary' :pressed='false' :to="'invoices/'+invoice.id" size='sm'>
              <b-icon variant='light' icon='pen' style='width: 20px; height: 20px'></b-icon>
            </b-button>
            <DeleteButton class='float-right mx-1' v-on:on-confirm='deleteInvoice' :id='invoice.id' :index='index'></DeleteButton>
          </b-col>
        </b-row>
    </template>
        <b-row>
          <b-col></b-col>
          <b-col>
            
          </b-col>
        </b-row>
    </b-card>
  </div>
</template>


<script>
import axios from "axios";
import Vue from "vue";
import { mapGetters, mapState } from "vuex";
import { SpinnerPlugin, ButtonPlugin,CardPlugin } from "bootstrap-vue";
import EmptyIndex from "../../components/EmptyIndex";
import LoadingPage from "../../components/LoadingPage";
import ErrorPage from "../../components/ErrorPage";
import DeleteButton from "../../components/DeleteButton";
Vue.use(SpinnerPlugin);
Vue.use(ButtonPlugin);
Vue.use(CardPlugin);
export default {
  name: "InvoiceIndex",
  components: {
    EmptyIndex,
    LoadingPage,
    ErrorPage,
    DeleteButton
  },
  data() {
    return {
      invoices: null
    };
  },
  mounted() {
    const app=this;
    if (app.isAuthenticated) {
      axios
        .get("/api/invoices/")
        .then(response => {
          app.invoices = response.data.invoices.data;
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
    }
  },
  computed: {
    ...mapGetters(["isAuthenticated"]),
    ...mapState({})
  }
};
</script>