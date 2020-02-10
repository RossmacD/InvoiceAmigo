<template>
  <div class='mt-5 text-center'>
    <b-spinner variant='secondary' label='Loading...'></b-spinner>
    <h4>Loading...</h4>
  </div>
</template>

<script>
import Vue from "vue";
import { SpinnerPlugin } from "bootstrap-vue";
Vue.use(SpinnerPlugin);

export default {
  name: "LoadingPage"
};
</script>
