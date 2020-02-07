<template>
  <div>
    <h1>Home page</h1>
    <h2 v-if='isProfileLoaded'>Welcome back, {{name}}!</h2>
    <b-row v-else>
      <b-col>
        <b-card bg-variant='default' header-bg-variant='dark' header-text-variant='white' header='Register' title='New around here?'>
          <b-card-text text-variant='dark'>Lets get started</b-card-text>
          <b-button to='/register' variant='primary'>Register</b-button>
        </b-card>
      </b-col>
      <b-col cols='4'>
        <b-card bg-variant='default' header-bg-variant='dark' header-text-variant='white'  title='Coming back?' header='Login'>
          <b-card-text >Login Here</b-card-text>
          <b-button to='/login' variant='primary'>Login</b-button>
        </b-card>
      </b-col>
    </b-row>
  </div>
</template>

<script>
import Vue from "vue";
import { mapGetters, mapState } from "vuex";
import { CardPlugin, ButtonPlugin, LayoutPlugin } from "bootstrap-vue";
Vue.use(CardPlugin);
Vue.use(ButtonPlugin);
Vue.use(LayoutPlugin);

export default {
  computed: {
    ...mapGetters(["getProfile", "isAuthenticated", "isProfileLoaded"]),
    ...mapState({
      authLoading: state => state.auth.status === "loading",
      name: state => `${state.user.profile.name}`
    })
  }
};
</script>
