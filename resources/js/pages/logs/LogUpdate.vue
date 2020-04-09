<template>
  <div>
    <LogCreate v-if='invoice' :invoice='invoice' editing></LogCreate>
    <LoadingPage v-else></LoadingPage>
  </div>
</template>

<script>
import Vue from "vue";
import axios from "axios";
import { ButtonPlugin, SpinnerPlugin } from "bootstrap-vue";
import { mapGetters, mapState } from "vuex";
import LogCreate from "./LogCreate";
import LoadingPage from "../../components/LoadingPage";
Vue.use(ButtonPlugin);
Vue.use(SpinnerPlugin);
export default {
  name: "LogUpdate",
  components: {
    LogCreate,
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
        .get("/api/logs/" + this.$route.params.id)
        .then(response => {
          app.invoice = response.data.invoice;
        })
        .catch(err => {
          console.log(err);
        });
    }
  }
};
</script>