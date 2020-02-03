<template>
  <div class='card'>
    <div class='card-header'>Register</div>
    <div class='card-body'>
      <b-form>
        <!-- <div class='form-group row'>
          <label for='name' class='col-md-4 col-form-label text-md-right'>Name</label>
          <div class='col-md-6'>
            <input id='name' type='text' class='form-control' name='name' required autocomplete='name' autofocus />
            <span class='invalid-feedback' role='alert'>
              <strong>{{ message }}</strong>
            </span>
          </div>
        </div>
        <div class='form-group row'>
          <label for='email' class='col-md-4 col-form-label text-md-right'>Email</label>

          <div class='col-md-6'>
            <input id='email' type='email' class='form-control' name='email' required autocomplete='email' />
            <span class='invalid-feedback' role='alert'>
              <strong>{{ message }}</strong>
            </span>
          </div>
        </div>

        <div class='form-group row'>
          <label for='password' class='col-md-4 col-form-label text-md-right'>Password</label>

          <div class='col-md-6'>
            <input id='password' type='password' class='form-control' name='password' required autocomplete='new-password' />

            <span class='invalid-feedback' role='alert'>
              <strong>{{ message }}</strong>
            </span>
          </div>
        </div>

        <div class='form-group row'>
          <label for='password-confirm' class='col-md-4 col-form-label text-md-right'>Password</label>
          <div class='col-md-6'>
            <input id='password-confirm' type='password' class='form-control' name='password_confirmation' required autocomplete='new-password' />
          </div>
        </div>
        <div class='form-group row mb-0'>
          <div class='col-md-6 offset-md-4'>
            <button type='submit' class='btn btn-primary'>Register</button>
          </div>
        </div> -->
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
      message: {
        name: "",
        email: "",
        password: ""
      }
    };
  },
  methods: {
    register() {
      console.log("test");
      let app = this;
      axios
        .post("/api/register", {
          name: app.name,
          email: app.email,
          password: app.password
        })
        .then(function(response) {
          console.log(response);
          app.name = response.data.name;
          app.email = response.data.email;
          localStorage.setItem("token", response.data.token);
        })
        .catch(function(error) {
          console.log(error.response.data.error);
          if (error.response.data.messages !== null) {
            app.message = error.response.data.messages;
          } else {
            app.message = error.response.data.error;
          }
        });
    }
  },
  mounted() {}
};
</script>