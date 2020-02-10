<template>
  <div class='text-center'>
    <h3 class='text-center mt-5'>You have no {{indexType}}s 😢</h3>
    <b-button :to="indexType+'s/create'" class='mt-1' variant='success' style='text-transform: capitalize;' >+ Add New {{indexType}}</b-button>
  </div>
</template>

<script>
import Vue from "vue";
import { ButtonPlugin } from "bootstrap-vue";
Vue.use(ButtonPlugin);

export default {
  name: "EmptyIndex",
  props: ["indexType"],
  date() {
    return {
      link: this.indexType + "/create"
    };
  }
};
</script>