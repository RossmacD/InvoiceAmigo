<template>
  <div>
    <div v-if='isProfileLoaded=true'>
      <h1>Your Dashboard</h1>
      <h2>Welcome back, {{name}}!</h2>
    </div>
    <ErrorPage v-else></ErrorPage>
  </div>
</template>

<script>
import Vue from "vue";
import { mapGetters, mapState } from "vuex";
import { CardPlugin, ButtonPlugin, LayoutPlugin } from "bootstrap-vue";
import ErrorPage from "../components/ErrorPage";
Vue.use(CardPlugin);
Vue.use(ButtonPlugin);
Vue.use(LayoutPlugin);

export default {
  name: "DashBoard",
  components: {
    ErrorPage
  },
  computed: {
    ...mapGetters(["getProfile", "isAuthenticated", "isProfileLoaded"]),
    ...mapState({
      authLoading: state => state.auth.status === "loading",
      name: state => `${state.user.profile.name}`
    })
  }
};
</script>
