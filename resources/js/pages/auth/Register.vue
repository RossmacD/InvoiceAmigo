<template>
  <div class='card'>
    <div class='card-header'>Register</div>
    <div class='card-body'>
      <b-form>
        <b-form-group label='Email Address' label-for='email'>
          <b-form-input id='email' type='email' name='email' required autocomplete='email' autofocus v-model='email'></b-form-input>
          <b-form-invalid-feedback v-if='messages.email' force-show>{{messages.email[0]}}</b-form-invalid-feedback>
        </b-form-group>

        <b-form-group label='Password'>
          <div class='col-md-6' label-for='password'>
            <b-form-input id='password' type='password' class='form-control' name='password' required autocomplete='current-password' v-model='password' @keydown.enter.native='register()' />
            <b-form-invalid-feedback force-show v-if='messages.password'>{{messages.password[0]}}</b-form-invalid-feedback>
          </div>
        </b-form-group>

        <b-form-group class='mb-0'>
          <div class='col-md-4'>
            <b-button v-on:click='register()' class='btn btn-primary'>Login</b-button>
            <!-- <b-button v-else class='btn btn-info'>
                <b-spinner small label='Loading...'></b-spinner>
            </b-button>-->
          </div>
        </b-form-group>
      </b-form>
    </div>
  </div>
</template>
<script>
import axios from "axios";
import { FormPlugin, ButtonPlugin } from "bootstrap-vue";
import Vue from "vue";
Vue.use(ButtonPlugin);
Vue.use(FormPlugin);
export default {
  name: "Register",
  data() {
    return {
      name: "",
      email: "",
      password: "",
      confirmPassword: "",
      messages: {
        name: "",
        email: "",
        password: ""
      }
    };
  },
  methods: {
    register() {
      axios
        .post("/api/register", {
          name: this.name,
          email: this.email,
          password: this.password
        })
        .then(function(response) {
          console.log(response);
          this.name = response.data.name;
          this.email = response.data.email;
          localStorage.setItem("token", response.data.token);
        })
        .catch(function(error) {
          console.log(error.response.data.error);
          if (error.response.data.messages !== null) {
            this.messages = error.response.data.messages;
          } else {
            this.messages = error.response.data.error;
          }
        });
    }
  },
  computed: {
    passState() {
      return this.password === this.confirmPassword;
    }
  }
};
</script>