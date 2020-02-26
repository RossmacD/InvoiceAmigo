<template>
  <LoadingPage></LoadingPage>
</template>

<script>
import axios from "axios";
import Vue from "vue";
import LoadingPage from "../../components/LoadingPage";
import { SOCIAL_AUTH_REQUEST } from "../../store/actions/auth";


export default {
    name:"callback",
    components:{
        LoadingPage
    },
  data() {
    return {

    };
  },
  mounted(){
    //   console.log(this.$route.query)
      this.$store
        .dispatch(SOCIAL_AUTH_REQUEST, this.$route.query)
        .then(() => {
          this.$router.push("/");
        })
        .catch(e => {
          this.messages = e.data.messages;
        });
  }
};
</script>
