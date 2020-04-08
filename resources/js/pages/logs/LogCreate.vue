<template>
  <div>
    <h3>Time Log:</h3>
    <EmailField v-on:email-update='getEmail' :messages='[]'></EmailField>
    <hr />
    <b-row>
      <b-col class='my-3'>
        <h4>Timers:</h4>
        <div class='customScroll' style='position:relative; height:50vh; overflow-y:auto; background-color:#dad5dc;border-radius: 15px;padding:15px'>
          <b-card-group deck v-if="!invoice.invoiceLines[0]"><b-card>
            Add a service to begin logging
            </b-card></b-card-group>
          <b-card-group  v-else deck v-for='invoiceLine in invoice.invoiceLines' :key='invoiceLine.id'>
            <b-card class='my-1'>
              <b-row no-body class no-gutters>
                <b-row no-gutters>
                  <b-col md='6' class='text-center'>
                    <b-icon icon='clock-fill' style='height:35%;width:35%;' />
                    <h4>{{invoiceLine.formattedTime}}</h4>
                    <div class='p-1'>
                      <b-progress class='mx-1' variant='success' :value='invoiceLine.formattedTime.substr(6,2)' striped></b-progress>
                    </div>
                    <b-progress class='mx-1' variant='info' :value='invoiceLine.formattedTime.substr(3,2)' striped></b-progress>
                  </b-col>
                  <b-col md='6'>
                    <b-card-body :title='invoiceLine.name'>
                      <b-card-text>{{invoiceLine.description}}</b-card-text>
                      <b-button @click='invoiceLine.running?invoiceLine.running=false:invoiceLine.running=true' block>
                        <span v-if='invoiceLine.running'>Stop</span>
                        <span v-else>Start</span>
                      </b-button>
                      <b-button @click='invoiceLine.sec=0;invoiceLine.running=false' block variant='dark'>Reset</b-button>
                    </b-card-body>
                  </b-col>
                </b-row>
              </b-row>
            </b-card>
          </b-card-group>
        </div>
      </b-col>
      <b-col class='my-3'>
        <h4>Your Serivces</h4>
        <b-list-group  style="height:50vh;overflow-y:auto;">
          <b-list-group-item>
            <vue-bootstrap-typeahead :data='searchResults' autocomplete='line_name' :serializer='s=>s.name' @hit='createTimer()' :minMatchingChars='1' v-model='keywords' @input='search()' prepend='' >
              <template v-slot:prepend>
                <b-input-group-text><b-icon icon="search" ></b-icon></b-input-group-text>
              </template>
            </vue-bootstrap-typeahead>
          </b-list-group-item>
          <b-list-group-item v-for="service in services" v-bind:key="service.id">
            {{service.name}}
            <b-button class='float-right' style="font-size: 0.8rem;" small>+Add</b-button>
          </b-list-group-item>
        </b-list-group>
        <b-button variant='success' v-on:click='submit()'>Save</b-button>
        <!-- <p>Will Round up to closet hour on save.</p> -->
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

export default {
  name: "LogCreate",
  components: {
    EmailField,
    BIcon,
    VueBootstrapTypeahead
  },
  props: {
    invoice: {
      type: Object,
      default() {
        return {
          user_email: "",
          currency: "eur",
          status:"draft",
          invoiceLines: [
          ],
        };
      }
    },
    editing: {
      type: Boolean,
      default: false
    }
  },
  data() {
    return {
      services:[],
      keywords: "",
      searchResults: [],
      timeCount: 0,
      messages: {
        email: ""
      },
    };
  },
  mounted() {
    const app=this;
    app.$nextTick(function() {
      window.setInterval(() => {
        app.timeCount++;
        app.invoice.invoiceLines.forEach(timer => {
          timer.running ? timer.sec++ : "";
          timer.formattedTime = new Date(timer.sec * 1000)
            .toISOString()
            .substr(11, 8);
        });
      }, 1000);
    });
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
  },
  methods: {
    getEmail(email) {
      this.invoice.user_email = email;
    },
    search() {
      const app = this;
      axios
        .get("/api/search/services", {
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
      this.invoice.invoiceLines.push({
        id: this.invoice.invoiceLines.length,
        sec: 0,
        formattedTime: "00:00:00",
        running: true,
        // attachedItem: attachedItem[0],
        name: attachedItem[0].name,
        description: attachedItem[0].description,
        cost: attachedItem[0].cost,
        rate_unit: attachedItem[0].rate_unit,
        // quantity: "",
        type: "service",
        dropText: "Hourly"
      });
    },
    submit(){
       const app = this;
      app.submiting = true;
      console.log(app.isAuthenticated)
      if (app.isAuthenticated) {
      
      axios.post("/api/logs", app.invoice)
      .then(response => {
              this.$router.push("/logs");
            })
            .catch(err => {
              console.log(err)
            });
        app.submiting = false;
      }
    }
  },
  computed: {
    ...mapGetters(["isAuthenticated"]),
    ...mapState({})
  }
};
</script>