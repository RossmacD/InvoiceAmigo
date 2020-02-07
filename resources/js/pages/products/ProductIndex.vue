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
    <EmptyIndex indexType='product' v-else-if='products.length===0'></EmptyIndex>
    <ul class='list-group py-3 mb-3' v-else>
      <li class='list-group-item my-2' v-for='(product,index) in products' v-bind:key='product.id'>
        <h5>{{ product.product_name }}</h5>
        <h4 class='float-right'>€{{ product.product_cost }} EUR</h4>
        <p>{{ product.product_description }}</p>
        <DeleteButton v-on:on-confirm='deleteProduct' :id='product.id' :index='index'></DeleteButton>
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
import DeleteButton from "../../components/DeleteButton";
import { SpinnerPlugin, ButtonPlugin } from "bootstrap-vue";
Vue.use(SpinnerPlugin);
Vue.use(ButtonPlugin);
export default {
  name: "ProductIndex",
  components: {
    EmptyIndex,
    DeleteButton
  },
  data() {
    return {
      products: null,
      deleteText: ""
    };
  },
  methods: {
    deleteProduct(id,index) {
      console.log("delete" + index);
      const app=this;
      axios
        .delete("/api/products/" + id)
        .then(function(response) {
          console.log(response);
          app.$delete(app.products, index) 
        })
        .catch(function(error) {
          console.log(error.response);
        });
    }
  },
  mounted() {
    this.token = localStorage.getItem("token");
    if (this.token !== null) {
      axios
        .get("/api/products/")
        .then(response => {
          this.products = response.data.products.data;
        })
        .catch(err => {});
    }
  }
};
</script>
