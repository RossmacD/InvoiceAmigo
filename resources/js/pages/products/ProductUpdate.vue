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
import { mapGetters, mapState } from "vuex";
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
  computed: {
    ...mapGetters(["isAuthenticated"]),
    ...mapState({})
  },
  mounted() {
    const app = this;
    if (app.isAuthenticated) {
      axios
        .get("/api/products/" + this.$route.params.id)
        .then(response => {
          app.product = response.data.product;
        })
        .catch(err => {
          console.log(err);
        });
    }
  }
};
</script>