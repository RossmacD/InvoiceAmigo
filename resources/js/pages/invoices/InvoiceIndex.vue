<template>
  <div>
    <h2 class='text-center'>Your Invoices</h2>
    <!-- <a href="{{route('products.create')}}" class="btn btn-primary ">New Product</a> -->
    <div v-if='!invoices' class='mt-5 text-center'>
      <b-spinner variant='secondary' label='Loading...'></b-spinner>
      <h4>Loading...</h4>
    </div>
    <!-- <div class='text-center' v-else-if='invoices.empty'>
      <h3 class='text-center mt-5'>You have no invoices 😢</h3>
      <b-button class='mt-1' variant='success'>+ Add New Invoice</b-button>
    </div>-->
    <EmptyIndex indexType='invoice' v-else-if='invoices.length===0'></EmptyIndex>
    <ul class='list-group py-3 mb-3' v-else>
      <li class='list-group-item my-2' v-for='invoice in invoices' v-bind:key='invoice.id'>
        <h5>{{ invoice.invoice_name }}</h5>
        <!-- <h4 class='float-right'>€{{ product.product_cost }} EUR</h4>
        <p>{{ product.product_description }}</p>-->
        <!-- <small class="float-right">product.created_at->diffForHumans() }}</small>
        <a href="{{route('products.show',$product->id)}}">View Details</a>-->
      </li>
    </ul>
  </div>
</template>


<script>
import axios from "axios";
import Vue from "vue";
import { mapGetters, mapState } from "vuex";
import { SpinnerPlugin, ButtonPlugin } from "bootstrap-vue";
import EmptyIndex from "../../components/EmptyIndex";
Vue.use(SpinnerPlugin);
Vue.use(ButtonPlugin);
export default {
  name: "InvoiceIndex",
  components: {
    EmptyIndex
  },
  data() {
    return {
      invoices: null
    };
  },
  mounted() {
    console.log(this.isAuthenticated);
    this.token = localStorage.getItem("token");
    if (this.token !== null) {
      axios
        .get("/api/invoices/")
        .then(response => {
          this.invoices = response.data.invoices.data;
          // if (!!this.invoices) this.invoices.empty = true;
        })
        .catch(err => {
          console.log(err);
        });
    }
  },
  methods:{
    
  },
  computed: {
    ...mapGetters(["isAuthenticated"]),
    ...mapState({})
  }
};
</script>