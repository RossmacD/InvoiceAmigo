<template>
  <div>
    <h2 class='text-center'>Your Products</h2>
    <!-- <a href="{{route('products.create')}}" class="btn btn-primary ">New Product</a> -->
    <ul class='list-group py-3 mb-3' v-if='products'>
      <li class='list-group-item my-2' v-for='product in products' v-bind:key='product.id'>
        <h5>{{ product.product_name }}</h5>
        <h4 class='float-right'>€{{ product.product_cost }} EUR</h4>
        <p>{{ product.product_description }}</p>
        <!-- <small class="float-right">product.created_at->diffForHumans() }}</small>
        <a href="{{route('products.show',$product->id)}}">View Details</a>-->
      </li>
    </ul>
    <div v-else class='mt-5 text-center'>
      <b-spinner variant='secondary' label='Loading...'></b-spinner>
      <h4>Loading...</h4>
    </div>
  </div>
</template>


<script>
import axios from "axios";
import Vue from "vue";
import { SpinnerPlugin } from "bootstrap-vue";
Vue.use(SpinnerPlugin);
export default {
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
        .then(response => (this.products = response.data.products.data))
        .catch(err => {});
    }
  }
};
</script>
