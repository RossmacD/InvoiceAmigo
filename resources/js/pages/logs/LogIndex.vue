<template>
  <div>
    <h2 class='mb-4'>
      Your Logs
      <b-button to='/logs/create' class='float-right'>+ New</b-button>
    </h2>
    <b-row>
      <b-card-group deck>
        <b-card class='my-2'>
          <h3>Ultan@gmail.com</h3>
          <b-list-group flush>
            <b-list-group-item>Web design: 1 Hour</b-list-group-item>
            <b-list-group-item>Web design: 1 Hour</b-list-group-item>
            <b-list-group-item>Web design: 1 Hour</b-list-group-item>
          </b-list-group>
          <template v-slot:footer>
            <b-button  to='/invoices/edit'>Create Invoice</b-button>
            <b-button to='/logs/create' variant='success' class='float-right'>Add Time</b-button>
          </template>
        </b-card>

        <b-card class='my-2 text-center ' border-variant="dark"  style="border-style: dashed!important;" footer-border-variant="dark">
            <div class="align-middle">
                <h3>New Log?</h3>
              Create a log for a new customer
            </div>
          <template v-slot:footer >
            <b-button to='/logs/create' variant="dark" style="border-style: dashed;" class='float-right'>New Log</b-button>
          </template>
        </b-card>
      </b-card-group>
    </b-row>
  </div>
</template>

<script>
import Vue from "vue";
import {
  SpinnerPlugin,
  ButtonPlugin,
  LayoutPlugin,
  CardPlugin
} from "bootstrap-vue";
Vue.use(SpinnerPlugin);
Vue.use(ButtonPlugin);
Vue.use(LayoutPlugin);
Vue.use(CardPlugin);

export default {
  data() {
    return {
      
    };
  }
};
</script>