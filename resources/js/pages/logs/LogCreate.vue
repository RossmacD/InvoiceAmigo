<template>
  <div>
    {{timeCount}}
    <h3>Recipient:</h3>
    <EmailField v-on:email-update='getEmail' :messages='[]'></EmailField>
    <hr />
    <vue-bootstrap-typeahead :data='searchResults' autocomplete='line_name' :serializer='s=>s.name' @hit='createTimer()' :minMatchingChars='1' v-model='keywords' @input='search()' />
    <hr />

    <b-row>
      <b-col class='my-3'>
        <h4>Timers:</h4>
        <div class='customScroll' style='position:relative; height:50vh; overflow-y:auto; background-color:#dad5dc;border-radius: 15px;padding:15px'>
          <b-card-group deck v-for='timer in timers' :key='timer.id'>
            <b-card class='my-1'>
              <b-row no-body class>
                <b-row no-gutters>
                  <b-col md='6' class='text-center'>
                    <b-icon icon='clock-fill' style='height:35%;width:35%;' />
                    <h4>{{timer.formattedTime}}</h4>
                    <div class='p-1'>
                      <b-progress class='mx-1' variant='success' :value='timer.formattedTime.substr(6,2)' striped></b-progress>
                    </div>
                    <b-progress class='mx-1' variant='info' :value='timer.formattedTime.substr(3,2)' striped></b-progress>
                  </b-col>
                  <b-col md='6'>
                    <b-card-body :title='timer.attachedItem.name'>
                      <b-card-text>{{timer.attachedItem.description}}</b-card-text>
                      <b-button @click='timer.running?timer.running=false:timer.running=true' block>
                        <span v-if='timer.running'>Stop</span>
                        <span v-else>Start</span>
                      </b-button>
                      <b-button @click='timer.sec=0;timer.running=false' block variant='dark'>Reset</b-button>
                    </b-card-body>
                  </b-col>
                </b-row>
              </b-row>
            </b-card>
          </b-card-group>
        </div>
      </b-col>
      <b-col>
        <h4>Your Products & Serivces</h4>
        <b-list-group>
          <b-list-group-item>
            Web design
            <b-form-checkbox class='float-right' switch name='check-button' />
          </b-list-group-item>
          <b-list-group-item>
            Website Testing
            <b-form-checkbox class='float-right' switch name='check-button' />
          </b-list-group-item>
          <b-list-group-item>
            Wireframes and heuristic analysis
            <b-form-checkbox class='float-right' switch name='check-button' />
          </b-list-group-item>
        </b-list-group>
        <b-button variant='success'>Save</b-button>
        <b-button variant='primary'>Create Invoice for {{email?email:"client"}}</b-button>
      </b-col>
    </b-row>
    <b-row>
      <b-col></b-col>
      <b-col></b-col>
    </b-row>
  </div>
</template>

<script>
import Vue from "vue";
import { mapGetters, mapState } from "vuex";
import {
  CardPlugin,
  ButtonPlugin,
  LayoutPlugin,
  ProgressPlugin,
  BIcon
} from "bootstrap-vue";
import VueBootstrapTypeahead from "vue-typeahead-bootstrap";

import EmailField from "../../components/EmailField";
Vue.use(CardPlugin);
Vue.use(ButtonPlugin);
Vue.use(LayoutPlugin);
Vue.use(ProgressPlugin);

// function clockRunning(){
//   var currentTime = new Date()
//   , timeElapsed = new Date(currentTime - timeBegan - stoppedDuration)
//   , hour = timeElapsed.getUTCHours()
//   , min = timeElapsed.getUTCMinutes()
//   , sec = timeElapsed.getUTCSeconds()
//   , ms = timeElapsed.getUTCMilliseconds();

//   clock.time =
//     zeroPrefix(hour, 2) + ":" +
//     zeroPrefix(min, 2) + ":" +
//     zeroPrefix(sec, 2) + "." +
//     zeroPrefix(ms, 3);
// };

export default {
  name: "LogCreate",
  components: {
    EmailField,
    BIcon,
    VueBootstrapTypeahead
  },
  data() {
    return {
      email: "",
      keywords: "",
      searchResults: [],
      timeCount: 0,
      messages: {
        email: ""
      },
      timers: [],
      timeBegan: null,
      timeStopped: null,
      stoppedDuration: 0,
      started: null,
      running: false
    };
  },
  mounted() {
    this.$nextTick(function() {
      window.setInterval(() => {
        this.timeCount++;
        this.timers.forEach(timer => {
          timer.running ? timer.sec++ : "";
          timer.formattedTime = new Date(timer.sec * 1000)
            .toISOString()
            .substr(11, 8);
        });
      }, 1000);
    });
  },
  methods: {
    getEmail(email) {
      this.email = email;
    },
    search() {
      const app = this;
      axios
        .get("/api/search/products", {
          params: { keywords: app.keywords }
        })
        .then(res => {
          app.searchResults = res.data.invoiceLines;
        })
        .catch(err => {
          console.log("CANT FETCH", err);
        });
    },
    createTimer() {
      const app = this;
      //Fill in Invoice Line after autocomplete is selected
      const attachedItem = this.searchResults.filter(
        result => result.name == app.keywords
      );
      this.timers.push({
        id: 0,
        sec: 0,
        formattedTime: "00:00:00",
        running: true,
        attachedItem: attachedItem[0]
      });
      // this.invoice.invoiceLines[index].description = newProduct[0].description;
      // this.invoice.invoiceLines[index].cost = newProduct[0].cost;
      // if ("undefined" === typeof newProduct[0]["rate_unit"]) {
      //   console.log("undefined");
      //   //Update the type + dropdown Text
      //   this.setDropText(index, "product", null);
      // } else {
      //   console.log("defined");
      //   this.invoice.invoiceLines[index].rate_unit = newProduct[0].rate_unit;
      //   this.setDropText(index, "service", newProduct[0].rate_unit);
      // }
      // this.invoice.invoiceLines[index].quantity = 1;
      // this.totalCost();
    }
  }
};
</script>