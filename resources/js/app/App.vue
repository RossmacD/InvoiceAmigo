<template>
  <div>
    <Navbar></Navbar>
    <main role='main' class='container mt-4'>
      <!-- Content -->
      <router-view></router-view>
    </main>
  </div>
</template>

<script>
import Vue from "vue";
import Navbar from "../components/Navbar.vue";
import { USER_REQUEST } from "../store/actions/user";

export default {
  name: "App",
  components: {
    Navbar
  },
  created: function() {
    if (!!localStorage.getItem("token")) {
      this.$store.dispatch(USER_REQUEST);
    }

    // axios.interceptors.response.use(undefined, function(err) {
    //   return new Promise(function(resolve, reject) {
    //     if (err.status === 401 && err.config && !err.config.__isRetryRequest) {
    //       // if you ever get an unauthorized, logout the user
    //       this.$store.dispatch(AUTH_LOGOUT);
    //       // you can also redirect to /login if needed !
    //     }
    //     throw err;
    //   });
    // });
  }
};
</script>

