<template>
  <div>
    <IndexBase itemName='products' :items='products' :loaded='loaded' :hitError='hitError' v-on:on-confirm='deleteProduct'></IndexBase>
  </div>
</template>


<script>
import axios from "axios";
import { mapGetters, mapState } from "vuex";
import Vue from "vue";
import IndexBase from "../../components/IndexBase";

export default {
  name: "ProductIndex",
  components: {
    IndexBase
  },
  data() {
    return {
      products: [],
      loaded: false,
      hitError: false
    };
  },
  methods: {
    deleteProduct(id, index) {
      console.log("delete" + index);
      const app = this;
      axios
        .delete("/api/products/" + id)
        .then(function(response) {
          app.$delete(app.products, index);
        })
        .catch(function(error) {
          console.log(error.response);
        });
    }
  },
  mounted() {
    const app = this;
    if (app.isAuthenticated) {
      axios
        .get("/api/products/")
        .then(response => {
          app.products = response.data.products.data;
          app.loaded = true;
        })
        .catch(err => {
          console.log(error);
          app.hitError = true;
        });
    }
  },
  computed: {
    ...mapGetters(["isAuthenticated"]),
    ...mapState({})
  }
};
</script>

