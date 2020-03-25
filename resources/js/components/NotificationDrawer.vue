<template>
  <div class='drawer'>
    <h1>Notifications:</h1>
    <b-nav vertical v-for='notification in notifications' :key='notification.id'>
      <!-- <b-nav-item :disabled="!!!notification.link">{{notification.message}}</b-nav-item> -->
      <b-nav-item :disabled="!!!notification.link" :to="notification.link">{{notification.message}}</b-nav-item>
    </b-nav>
  </div>
</template>

<script>
import axios from "axios";
import Vue from "vue";
import { mapGetters, mapState } from "vuex";


export default {
  name: "Notifications",
  data() {
    return {};
  },
  
  computed: {
    ...mapGetters(["notifications", "getProfile"]),
    ...mapState({
      name: state => `${state.user.profile.name}`,
      id: state => `${state.user.profile.id}`
    })
  }
};
</script>

<style>
.drawer {
  position: fixed;
  background-color: white;
  width: 40%;
  height: 100%;
  right: 0px;
  top: 66.74px;
  z-index: 99999999;
}
@media only screen 
   and (max-width : 425px) {
     .drawer {
    width:90%;
   }
}
</style>