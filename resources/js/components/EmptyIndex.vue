<template>
  <div class='text-center'>
    <h3 class='text-center mt-5'>You have no {{indexType}}s 😢</h3>
    <b-button class='mt-1' variant='success'>+ Add New Invoice</b-button>
  </div>
</template>

<script>
import Vue from "vue";
import { ButtonPlugin } from "bootstrap-vue";
Vue.use(ButtonPlugin);

export default {
  name: "EmptyIndex",
  props: ["indexType"]
};
</script>