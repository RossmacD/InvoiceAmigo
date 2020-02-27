<template>
  <div class='drawer'>
    <b-nav vertical>
      <b-nav-item disabled>
        <h1>Notifications:</h1>
      </b-nav-item>
      <b-nav-item disabled v-for='message in messages' :key='message'>{{message}}</b-nav-item>
    </b-nav>
  </div>
</template>

<script>
import axios from "axios";
import Vue from "vue";
import { mapGetters, mapState } from "vuex";

Pusher.logToConsole = true;

export default {
  name: "Notifications",
  data() {
    return {
      messages: []
    };
  },
  mounted() {
    const app = this;
    let pusher = new Pusher(process.env.MIX_PUSHER_APP_KEY, {
      cluster: process.env.MIX_PUSHER_APP_CLUSTER,
      forceTLS: true
    });

    let channel = pusher.subscribe("notifications");
    channel.bind("notification", function(data) {
      app.messages.push(JSON.stringify(data));
    });
  },
  computed: {
    ...mapGetters(['notifications']),
    ...mapState({})
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