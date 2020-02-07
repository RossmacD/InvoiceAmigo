<template>
  <div>
    <ProductCreate v-if='product' :product='product' editing></ProductCreate>
    <LoadingPage v-else></LoadingPage>
  </div>
</template>

<script>
import Vue from "vue";
import axios from "axios";
import { ButtonPlugin, SpinnerPlugin } from "bootstrap-vue";
import ProductCreate from "./ProductsCreate";
import LoadingPage from "../../components/LoadingPage";
Vue.use(ButtonPlugin);
Vue.use(SpinnerPlugin);
export default {
  name: "ProductUpdate",
  components: {
    ProductCreate,
    LoadingPage
  },
  data() {
    return {
      product: ""
    };
  },
  methods: {},
  mounted() {
    const app = this;
    this.token = localStorage.getItem("token");
    if (this.token !== null) {
      axios
        .get("/api/products/" + this.$route.params.id)
        .then(response => {
          app.product = {
            id: response.data.product.id,
            name: response.data.product.product_name,
            description: response.data.product.product_description,
            cost: response.data.product.product_cost
          };
        })
        .catch(err => {
          console.log(error);
        });
    }
  }
};
</script>