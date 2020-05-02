<template>
  <!-- <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <router-link class="navbar-brand" to="/example">Laravel/Vue</router-link>-->
  <div>
    <b-navbar toggleable='md' type='dark' variant='dark'>
      <b-navbar-brand to='/'>
        <img src='/img/brand/navLogoInverted.png' height='44' class='d-inline-block align-top' alt='InvoiceAmigo' />
        <!-- InvoiceAmigo -->
      </b-navbar-brand>
      <b-navbar-toggle target='navbarCollapse' class='float-right'></b-navbar-toggle>
      <b-collapse id='navbarCollapse' is-nav>
        <!-- <b-navbar-nav> -->
          <!-- <b-nav-item active to='/dash' v-if='isAuthenticated'>Dash</b-nav-item> -->
        <!-- </b-navbar-nav> -->
        <b-navbar-nav class='ml-auto'>
          <!-- Show when logged in -->
          <b-nav-item active to='/invoices' v-if='isAuthenticated'>Invoices</b-nav-item>
          <b-nav-item active to='/products' v-if='isBusiness'>Products</b-nav-item>
          <b-nav-item active to='/services' v-if='isBusiness'>Services</b-nav-item>
          <b-nav-item active to='/logs' v-if='isBusiness '>Logger</b-nav-item>

          <b-nav-item-dropdown v-if='isAuthenticated' id='my-nav-dropdown' text='Account' toggle-class='nav-link-custom' right>
            <b-dropdown-item active to='/settings'>Settings</b-dropdown-item>
            <b-dropdown-item active to='/cpanel'>cPanel</b-dropdown-item>
            <b-dropdown-item active @click='logout()'>Log Out</b-dropdown-item>
          </b-nav-item-dropdown>
          <!-- <b-nav-item active> -->
          <b-nav-item active @click='switchDrawer' v-if='isAuthenticated'>
            <b-icon icon='bell-fill' font-scale='1.5'></b-icon>
            <b-badge pill variant='info' v-if='notificationCount!=0'>{{notificationCount}}</b-badge>
          </b-nav-item>

          <!-- <b-form-checkbox switch v-model='notificationDrawer'>
              
          </b-form-checkbox>-->

          <!-- </b-nav-item> -->
          <!-- Show when not logged in -->
          <b-nav-item class='text-dark nav-login'  to='/login' v-if='!isAuthenticated'>Login</b-nav-item>
          <b-nav-item class='text-dark nav-login'  to='/register' v-if='!isAuthenticated'>Register</b-nav-item>
        </b-navbar-nav>
      </b-collapse>
    </b-navbar>
    <NotificationDrawer v-if='notificationDrawer' v-on:closeDrawer='switchDrawer'></NotificationDrawer>
  </div>
</template>

<script>
import Vue from "vue";
import {
  ButtonPlugin,
  NavPlugin,
  NavbarPlugin,
  BIcon,
  BadgePlugin
} from "bootstrap-vue";
import { mapGetters, mapState } from "vuex";
import { AUTH_LOGOUT } from "../store/actions/auth";
import NotificationDrawer from "./NotificationDrawer";
// Install BootstrapVue
Vue.use(ButtonPlugin);
Vue.use(NavPlugin);
Vue.use(NavbarPlugin);
Vue.use(BadgePlugin);

export default {
  props:{
  },
  data() {
    return {
      notificationDrawer: false
    };
  },
  components: {
    BIcon,
    NotificationDrawer
  },
  methods: {
    logout() {
      this.$store
        .dispatch(AUTH_LOGOUT)
        .then(() => {
          this.$router.push("/login");
        })
        .catch(e => {
          console.log(e);
        });
    },
    switchDrawer() {
        if(this.notificationDrawer){
          this.notificationDrawer = false;
          
        }else{
          this.notificationDrawer = true
        }
        this.$emit("drawerOpen",this.notificationDrawer);
    }
  },
  computed: {
    ...mapGetters([
      "getProfile",
      "isAuthenticated",
      "isProfileLoaded",
      "isBusiness",
      "notifications"
    ]),
    ...mapState({
      authLoading: state => state.auth.status === "loading",
      name: state => `${state.user.profile.name}`,
      notificationCount: state =>
        !!state.notification.notifications
          ? state.notification.notifications.length
          : 0
    })
  }
};
</script>

