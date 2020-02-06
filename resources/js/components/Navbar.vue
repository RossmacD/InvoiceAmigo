<template>
  <!-- <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <router-link class="navbar-brand" to="/example">Laravel/Vue</router-link>-->
  <b-navbar toggleable='md' type='dark' variant='dark'>
    <b-navbar-brand to='/'>
      <img src='/img/brand/navLogoInverted.png' height='45' class='d-inline-block align-top' alt='InvoiceAmigo' />
      <!-- InvoiceAmigo -->
    </b-navbar-brand>
    <b-navbar-toggle target='navbarCollapse' class='float-right'></b-navbar-toggle>
    <b-collapse id='navbarCollapse' is-nav>
      <b-navbar-nav class='ml-auto'>
        <!-- Show when logged in -->
        <b-nav-item active to='/invoices' v-if='isAuthenticated'>Invoices</b-nav-item>
        <b-nav-item active to='/products' v-if='isAuthenticated'>Products</b-nav-item>
        <b-nav-item active @click='logout()' v-if='isAuthenticated'>Log Out</b-nav-item>
        <!-- Show when not logged in -->
        <b-nav-item class='btn btn-success text-dark' active to='/login' v-if='!isAuthenticated'>Login</b-nav-item>
        <b-nav-item class='btn btn-success text-dark' active to='/register' v-if='!isAuthenticated'>Register</b-nav-item>
      </b-navbar-nav>
    </b-collapse>
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
      this.$store
        .dispatch(AUTH_LOGOUT)
        .then(() => {
          this.$router.push("/login");
        })
        .catch(e => {console.log(e)});
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