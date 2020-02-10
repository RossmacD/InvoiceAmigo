<template>
  <div>
    <IndexBase itemName='services' :items='services' :loaded="loaded" :hitError="hitError"></IndexBase>
  </div>
</template>


<script>
import Vue from "vue";
import axios from "axios";
import IndexBase from "../../components/IndexBase";
import { mapGetters, mapState } from "vuex";
export default {
  name: "ServiceIndex",
  components: {
    IndexBase
  },
  data() {
    return {
      services: [],
      loaded:false,
      hitError:false
    };
  },
  methods: {},
  mounted() {
    const app = this;
    if (app.isAuthenticated) {
      axios
        .get("/api/services")
        .then(response => {
          // console.log(response.data);
          app.services = response.data.services.data;
          app.loaded=true
        })
        .catch(err => {
          console.log(err);
          app.hitError=true;
        });
    }
  },
  computed: {
    ...mapGetters(["isAuthenticated"]),
    ...mapState({})
  }
};
</script>
