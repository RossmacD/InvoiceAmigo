<template>
  <div class='card'>
    <div class='card-header'>Customers:</div>
    <div class='card-body' v-for='customer in customers' v-bind:key='customer'>
      <h4>Name</h4>
      {{ customer.first_name }} {{ customer.last_name }}
      <h4>Email</h4>
      {{ customer.email }}
    </div>
  </div>
</template>

<script>
import axios from "axios";
export default {
  data() {
    return {
      customers: null
    };
  },
  mounted() {
    axios
      .get("http://localhost:8000/api/customer/")
      .then(response => (this.customers = response.data.data));
  }
};
</script>
