<template>
  <div>
    <InvoiceCreate v-if='invoice' :invoice='invoice' editing></InvoiceCreate>
    <LoadingPage v-else></LoadingPage>
  </div>
</template>

<script>
import Vue from "vue";
import axios from "axios";
import { ButtonPlugin, SpinnerPlugin } from "bootstrap-vue";
import { mapGetters, mapState } from "vuex";
import InvoiceCreate from "./InvoiceCreate";
import LoadingPage from "../../components/LoadingPage";
Vue.use(ButtonPlugin);
Vue.use(SpinnerPlugin);
export default {
  name: "InvoiceUpdate",
  components: {
    InvoiceCreate,
    LoadingPage
  },
  data() {
    return {
      invoice: ""
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
        .get("/api/invoices/" + this.$route.params.id)
        .then(response => {
          app.invoice = response.data.invoice;
        })
        .catch(err => {
          console.log(error);
        });
    }
  }
};
</script>