<template>
  <div class='card'>
    <div class='card-header'>Login</div>
    <div class='card-body'>
      <b-form>
        <b-form-group label='Email Address' label-for='email'>
          <b-form-input id='email' type='email' name='email' required autocomplete='email' autofocus v-model='email'></b-form-input>
          <b-form-invalid-feedback v-if='messages.email' force-show>{{messages.email[0]}}</b-form-invalid-feedback>
        </b-form-group>

        <b-form-group label='Password' label-for='password'>
          <b-form-input id='password' type='password' class='form-control' name='password' required autocomplete='current-password' v-model='password' @keydown.enter.native='login()' />
          <b-form-invalid-feedback force-show v-if='messages.password'>{{messages.password[0]}}</b-form-invalid-feedback>
        </b-form-group>

        <b-form-group label label-for='remember'>
          <b-form-checkbox id='remember' name='remember' value='remember'>Remember Me</b-form-checkbox>
        </b-form-group>

        <b-form-group label label-for='login' class='mb-0'>
          <b-button id='login' v-on:click='login()' class='btn btn-primary' v-if='!authLoading'>Login</b-button>
          <b-button v-else class='btn btn-info'>
            <b-spinner small label='Loading...'></b-spinner>
          </b-button>
          <!--                                
                                <a class="btn btn-link" href="{{ route('password.request') }}">
                                    Forgot your password
          </a>-->
        </b-form-group>

        <!-- <div class="col-md-8 col-md-offset-4">
                            <a href="{{url('/redirect')}}" class="btn btn-primary">Login with Google</a>
        </div>-->
      </b-form>
    </div>
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
  name: "Login",
  data() {
    return {
      name: "",
      email: "",
      password: "",
      messages: {
        email: "",
        password: ""
      }
    };
  },
  methods: {
    login() {
      this.$store
        .dispatch(AUTH_REQUEST, { email: this.email, password: this.password })
        .then(() => {
          this.$router.push("/");
        })
        .catch(e => {
          this.messages = e.data.messages;
        });
    }
  },
  computed: {
    ...mapGetters(["isProfileLoaded"]),
    ...mapState({
      authLoading: state => state.auth.status === "loading"
    }),
    validationState() {}
  }
};
</script>
