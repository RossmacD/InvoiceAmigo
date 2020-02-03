<template>
  <div>
    <h2 class='text-center'>
      Your Products
      <b-button to='/products/create' class='float-right'>+ New</b-button>
    </h2>

    <div v-if='!products' class='mt-5 text-center'>
      <b-spinner variant='secondary' label='Loading...'></b-spinner>
      <h4>Loading...</h4>
    </div>
    <EmptyIndex indexType='product' v-else-if='products.empty'></EmptyIndex>
    <ul class='list-group py-3 mb-3' v-else>
      <li class='list-group-item my-2' v-for='product in products' v-bind:key='product.id'>
        <h5>{{ product.product_name }}</h5>
        <h4 class='float-right'>€{{ product.product_cost }} EUR</h4>
        <p>{{ product.product_description }}</p>
        <!-- <small class="float-right">product.created_at->diffForHumans() }}</small>
        <a href="{{route('products.show',$product->id)}}">View Details</a>-->
      </li>
    </ul>
  </div>
</template>


<script>
import axios from "axios";
import Vue from "vue";
import EmptyIndex from "../../components/EmptyIndex";
import { SpinnerPlugin, ButtonPlugin } from "bootstrap-vue";
Vue.use(SpinnerPlugin);
Vue.use(ButtonPlugin);
export default {
  name: "ProductIndex",
  components: {
    EmptyIndex
  },
  data() {
    return {
      products: null
    };
  },
  mounted() {
    this.token = localStorage.getItem("token");
    if (this.token !== null) {
      axios
        .get("/api/products/")
        .then(response => {
          this.products = response.data.products.data;
          //if (!!this.products) this.products.empty = true;
        })
        .catch(err => {});
    }
  }
};
</script>
