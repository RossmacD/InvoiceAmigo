<template>
  <div class='drawer'>
    <b-nav vertical>
      <b-nav-item disabled>
        <h1>Notifications:</h1>
      </b-nav-item>
      <b-nav-item disabled v-for='notification in notifications' :key='notification'>{{notification.message}}</b-nav-item>
    </b-nav>
  </div>
</template>

<script>
import axios from "axios";
import Vue from "vue";
import { mapGetters, mapState } from "vuex";
import { ADD_NOTIFICATIONS } from "../store/actions/notification";

Pusher.logToConsole = true;

export default {
  name: "Notifications",
  data() {
    return {
    };
  },
  mounted() {
    const app = this;
    let pusher = new Pusher(process.env.MIX_PUSHER_APP_KEY, {
      cluster: process.env.MIX_PUSHER_APP_CLUSTER,
      forceTLS: true
    });

    let channel = pusher.subscribe("notifications." + this.id);
    channel.bind("notification", function(data) {
        // data=data);
      app.$store.dispatch(ADD_NOTIFICATIONS, data);
    });
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
  width: 500px;
  height: 100%;
  right: 0px;
  top: 66.74px;
  z-index: 99999999;
}
</style>