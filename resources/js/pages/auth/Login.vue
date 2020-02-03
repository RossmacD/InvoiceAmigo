<template>
  <div class='card'>
    <div class='card-header'>Login</div>
    <div class='card-body'>
      <b-form>
        <b-form-group class='form-group row'>
          <label for='email' class='col-md-4 col-form-label'>Email Address</label>
          <div class='col-md-6'>
            <b-form-input id='email' type='email' class='form-control' name='email' required autocomplete='email' autofocus v-model='email'></b-form-input>
            <b-form-invalid-feedback v-if='message.email' force-show>{{message.email[0]}}</b-form-invalid-feedback>
            <!-- <span class='invalid-feedback' role='alert' v-if='message'>
              <strong>{{ message }}</strong>
            </span>-->
          </div>
        </b-form-group>

        <b-form-group class='form-group row'>
          <label for='password' class='col-md-4 col-form-label'>Password</label>
          <div class='col-md-6'>
            <b-form-input id='password' type='password' class='form-control' name='password' required autocomplete='current-password' v-model='password' @keydown.enter.native="login()"/>
            <b-form-invalid-feedback force-show v-if='message.password'>{{message.password[0]}}</b-form-invalid-feedback>
          </div>
        </b-form-group>

        <div class='form-group row'>
          <div class='col-md-4'>
            <div class='form-check'>
              <input class='form-check-input' type='checkbox' name='remember' id='remember' />
              <label class='form-check-label' for='remember'>Remember Me</label>
            </div>
          </div>
        </div>

        <div class='form-group row mb-0'>
          <div class='col-md-4'>
            <b-button v-on:click='login()' class='btn btn-primary'>Login</b-button>

            <!--                                
                                <a class="btn btn-link" href="{{ route('password.request') }}">
                                    Forgot your password
            </a>-->
          </div>
        </div>

        <!-- <div class="col-md-8 col-md-offset-4">
                            <a href="{{url('/redirect')}}" class="btn btn-primary">Login with Google</a>
        </div>-->
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
import { AUTH_REQUEST } from "../../store/actions/auth";

export default {
  name: "Login",
  data() {
    return {
      name: "",
      email: "",
      password: "",
      message: {
        email: "",
        password: "",
        email: ""
      }
    };
  },
  methods: {
    login() {
      this.$store
        .dispatch(AUTH_REQUEST, { email: this.email, password: this.password })
        .then(() => {
          this.$router.push("/");
        });
    }
  }
};
</script>
