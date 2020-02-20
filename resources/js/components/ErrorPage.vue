<template>
  <div class='mt-5 text-center'>
<b-icon variant="danger" icon="alert-triangle" style="width: 200px; height: 200px;"></b-icon>
    <h1>OH NO!</h1>
    <h4>
      Error retrieving data. It was probably Ultans fault.
      <br />Are you connected to the internet?
    </h4>
  </div>
</template>

<script>
import Vue from "vue";
import { BIcon } from "bootstrap-vue";
export default {
  name: "ErrorPage",
  components: { BIcon }
};
</script>
