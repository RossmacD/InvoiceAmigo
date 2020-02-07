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
        <b-row>
          <b-col>
            <h5>{{ product.product_name }}</h5>
            <p>{{ product.product_description }}</p>
          </b-col>
          <b-col>
            <b-button class='float-right' variant='secondary' :pressed='false' :to="'/products/'+product.id" size='sm'>
              <b-icon variant='light' icon='pen' style='width: 20px; height: 20px'></b-icon>
            </b-button>
            <DeleteButton class='float-right' v-on:on-confirm='deleteProduct' :id='product.id' :index='index'></DeleteButton>
          </b-col>
        </b-row>
        <b-row>
          <b-col></b-col>
          <b-col>
            <h4 class='float-right'>€{{ product.product_cost }} EUR</h4>
          </b-col>
        </b-row>
      </li>
    </ul>
  </div>
</template>


<script>
import axios from "axios";
import Vue from "vue";
import EmptyIndex from "../../components/EmptyIndex";
import DeleteButton from "../../components/DeleteButton";
import { SpinnerPlugin, ButtonPlugin, LayoutPlugin } from "bootstrap-vue";
Vue.use(SpinnerPlugin);
Vue.use(ButtonPlugin);
Vue.use(LayoutPlugin);
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
    deleteProduct(id, index) {
      console.log("delete" + index);
      const app = this;
      axios
        .delete("/api/products/" + id)
        .then(function(response) {
          console.log(response);
          app.$delete(app.products, index);
        })
        .catch(function(error) {
          console.log(error.response);
        });
    },
    
  },
  mounted() {
    this.token = localStorage.getItem("token");
    if (this.token !== null) {
      axios
        .get("/api/products/")
        .then(response => {
          this.products = response.data.products.data;
        })
        .catch(err => {console.log(error)});
    }
  }
};
</script>
