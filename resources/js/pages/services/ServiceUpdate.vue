<template>
  <div>
    <ServiceCreate v-if='service' :service='service' editing></ServiceCreate>
    <LoadingPage v-else></LoadingPage>
  </div>
</template>

<script>
import Vue from "vue";
import axios from "axios";
import { ButtonPlugin, SpinnerPlugin } from "bootstrap-vue";
import { mapGetters, mapState } from "vuex";
import ServiceCreate from "./ServicesCreate";
import LoadingPage from "../../components/LoadingPage";
Vue.use(ButtonPlugin);
Vue.use(SpinnerPlugin);
export default {
  name: "ProductUpdate",
  components: {
    ServiceCreate,
    LoadingPage
  },
  data() {
    return {
      service: ""
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
        .get("/api/services/" + this.$route.params.id)
        .then(response => {
          app.service = response.data.service;
        })
        .catch(err => {
          console.log(err);
        });
    }
  }
};
</script>