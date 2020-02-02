<template>
  <!-- <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <router-link class="navbar-brand" to="/example">Laravel/Vue</router-link>-->
  <b-navbar toggleable='md' type='dark' variant='dark'>
    <b-navbar-brand to='/'>InvoiceAmigo</b-navbar-brand>
    <b-navbar-toggle target='navbarCollapse'></b-navbar-toggle>
    <b-navbar-nav class='ml-auto'>
      <b-collapse id='navbarCollapse' is-nav>
        <b-nav-item active to='/products' v-if='token'>Products</b-nav-item>
        <b-nav-item active @click='logout()' v-if='token'>Log Out</b-nav-item>

        <b-nav-item active to='/login' v-else>Login</b-nav-item>
      </b-collapse>
    </b-navbar-nav>
  </b-navbar>
</template>

<script>
import Vue from "vue";
import { ButtonPlugin, NavPlugin, NavbarPlugin } from "bootstrap-vue";
// Install BootstrapVue
Vue.use(ButtonPlugin);
Vue.use(NavPlugin);
Vue.use(NavbarPlugin);
export default {
  data() {
    return {
      token: localStorage.getItem("token")
    };
  },
  methods: {
    logout() {
      this.token = localStorage.getItem("token");
      let app = this;
      axios
        .get("/api/logout", {
          headers: {
            Authorization: "Bearer " + this.token
          }
        })
        .then(response => {
          console.log(response);
          localStorage.removeItem("token");
          this.token = null;
        })
        .catch(function(error) {
          console.log(error);
        });
    }
  },
  updated() {
    this.token = localStorage.getItem("token");
  },
  mounted() {
    this.token = localStorage.getItem("token");
  }
};
</script>