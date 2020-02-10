<template>
  <div>
    <h3 class='text-center'>
      <span v-if='editing'>Update</span>
      <span v-else>Create</span> Service
    </h3>
    <b-form>
      <b-form-group label='Service Name' label-for='service_name'>
        <b-form-input :state='nameState' aria-describedby='input-live-feedback' id='service_name' type='text' name='service_name' required autocomplete='service_name' autofocus v-model='service.name'></b-form-input>
        <b-form-invalid-feedback id='input-live-feedback'>Enter at least 5 letters</b-form-invalid-feedback>
      </b-form-group>
      <b-form-group label='Service Description' label-for='service_description'>
        <b-form-input id='service_description' type='text' name='service_description' required autocomplete='service_description' autofocus v-model='service.description'></b-form-input>
        <b-form-invalid-feedback v-if='messages.service.description' force-show>{{messages.service.description[0]}}</b-form-invalid-feedback>
      </b-form-group>
        <b-form-group label='Service Rate' label-for='service_rate'>
          <b-form-input id='service_rate' type='number' name='service_rate' required autocomplete='service_rate' autofocus v-model='service.rate'></b-form-input>
          <b-form-invalid-feedback v-if='messages.service.rate' force-show>{{messages.service.rate[0]}}</b-form-invalid-feedback>
        </b-form-group>
        <b-form-group label='Service Rate' label-for='service_rate'>
          <b-form-select plain v-model='service.rate_unit' :options='options' class='mt-3'></b-form-select>
        </b-form-group>
     

      <b-form-group class='mb-0'>
        <div class='col-md-4'>
          <b-button v-on:click='submit()' v-if='!submiting' class='btn btn-primary'>
            <span v-if='editing'>Update</span>
            <span v-else>Create</span>
          </b-button>
          <b-button v-else class='btn btn-info'>
            <b-spinner small label='Loading...'></b-spinner>
          </b-button>
        </div>
      </b-form-group>
    </b-form>
  </div>
</template>

<script>
import axios from "axios";
import { FormPlugin, ButtonPlugin, SpinnerPlugin } from "bootstrap-vue";
import Vue from "vue";
import { mapGetters, mapState } from "vuex";
import { AUTH_REQUEST } from "../../store/actions/auth";
Vue.use(SpinnerPlugin);
Vue.use(ButtonPlugin);
Vue.use(FormPlugin);

export default {
  name: "ServiceCreate",
  props: {
    service: {
      type: Object,
      default: function() {
        return {
          name: "",
          description: "",
          rate: "",
          rate_unit: "hour"
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
      messages: {
        service: {
          name: [],
          description: [],
          rate: [],
          rate_unit: []
        }
      },
      options: [
        { value: "hour", text: "Hourly" },
        { value: "day", text: "Daily" },
        { value: "week", text: "Weekly" }
      ],
      submiting: false
    };
  },
  methods: {
    submit() {
      const app = this;
      app.submiting = true;
      if (app.isAuthenticated) {
        if (app.editing) {
          axios
            .put("/api/services/" + app.service.id, app.service)
            .then(response => {
              this.$router.push("/services");
            })
            .catch(err => {
              console.log(err.response);
            });
        } else {
          axios
            .post("/api/services/", app.service)
            .then(response => {
              this.$router.push("/services");
            })
            .catch(err => {});
        }

        app.submiting = false;
      }
    }
  },
  computed: {
    nameState() {
      if (this.service.name.length == 0) {
        return null;
      } else if (this.service.name.length > 4) {
        return true;
      } else {
        return false;
      }
    },
    ...mapGetters(["isAuthenticated"]),
    ...mapState({})
  }
};
</script>