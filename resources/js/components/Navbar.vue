<template>
  <!-- <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <router-link class="navbar-brand" to="/example">Laravel/Vue</router-link>-->
  <b-navbar toggleable='md' type='dark' variant='dark'>
    <b-navbar-brand to='/'>InvoiceAmigo</b-navbar-brand>
    <b-navbar-toggle target='navbarCollapse'></b-navbar-toggle>
    <b-navbar-nav class='ml-auto'>
      <b-collapse id='navbarCollapse' is-nav>
        <!-- Show when logged in -->
        <b-nav-item active to='/products' v-if='isAuthenticated'>Products</b-nav-item>
        <b-nav-item active @click='logout()' v-if='isAuthenticated'>Log Out</b-nav-item>
        <!-- Show when not logged in -->
        <b-nav-item active to='/login' v-else>Login</b-nav-item>
      </b-collapse>
    </b-navbar-nav>
  </b-navbar>
</template>

<script>
import Vue from "vue";
import { ButtonPlugin, NavPlugin, NavbarPlugin } from "bootstrap-vue";
import { mapGetters, mapState } from "vuex";
import { AUTH_LOGOUT } from "../store/actions/auth";

// Install BootstrapVue
Vue.use(ButtonPlugin);
Vue.use(NavPlugin);
Vue.use(NavbarPlugin);

export default {
  methods: {
    logout() {
      this.$store.dispatch(AUTH_LOGOUT).then(() => {
        this.$router.push("/login");
      });
    }
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