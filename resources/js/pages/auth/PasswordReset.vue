<template>
  <div class='card'>
    <div class='card-header'>Set Password</div>
    <div class='card-body'>
      <b-form>
        <b-form-group label='Email Address' label-for='email'>
            <b-form-input id='email' type='email' name='email' required autocomplete='email' :disabled='true' v-model='pwResetInfo.email'></b-form-input>
            <!-- <b-form-invalid-feedback v-if='message' force-show>{{message[0]}}</b-form-invalid-feedback> -->
        </b-form-group>

        <b-form-group label='Name' label-for='name'>
          <b-form-input id='name' type='text' class='form-control' name='name' required autocomplete='name' v-model='pwResetInfo.name' :state='nameValid' />
          <b-form-invalid-feedback v-if='messages.name'>{{messages.name[0]}}</b-form-invalid-feedback>
        </b-form-group>
        <b-form-group label='Password' label-for='password'>
          <b-form-input id='password' type='password' class='form-control' name='password' required autocomplete='current-password' v-model='pwResetInfo.password' @keydown.enter.native='login()' />
          <b-form-invalid-feedback force-show v-if='messages.password'>{{messages.password[0]}}</b-form-invalid-feedback>
        </b-form-group>

        <b-form-group label label-for='set_password' class='mb-0'>
          <b-button id='set_password' v-on:click='setPassword()' class='btn btn-primary' v-if='!settingPw'>Set Password</b-button>
          <b-button v-else class='btn btn-info'>
            <b-spinner small label='Loading...'></b-spinner>
          </b-button>
        </b-form-group>

      </b-form>
    </div>
  </div>
</template>

<script>
import Vue from "vue";
import axios from "axios";
import { ButtonPlugin, SpinnerPlugin, FormPlugin } from "bootstrap-vue";
import { mapGetters, mapState } from "vuex";
import {AUTH_REQUEST} from "../../store/actions/auth"
import PasswordReset from "./PasswordReset";
import LoadingPage from "../../components/LoadingPage";
Vue.use(ButtonPlugin);
Vue.use(SpinnerPlugin);
Vue.use(FormPlugin);
export default {
  name: "PasswordReset",
  components: {
    LoadingPage
  },
  data() {
    return {
      loaded: false,
      settingPw: false,
      pwResetInfo: "",
      name: "",
      password: "",
      messages: {
        name: [],
        password: []
      }
    };
  },
  methods: {
    setPassword() {
      this.settingPw = true;
      if (!this.messages.confirmPassword) {
        let app = this;
        axios
          .post("/api/password/reset", {
            name: app.pwResetInfo.name,
            email: app.pwResetInfo.email,
            password: app.pwResetInfo.password,
            password_confirmation: app.pwResetInfo.password,
            token: app.pwResetInfo.token
          })
          .then(function(response) {
            console.log("Yes - response wokred")
            app.settingPw = false;
            console.log("Logging In")
            app.login();
          })
          .catch(function(error) {
            app.settingPw = false;
            console.log(error);
            console.log(error.response);
            app.messages = error.response;
          });
        app.settingPw = false;
      }
    },
    login() {
      const app=this;
      this.$store
        .dispatch(AUTH_REQUEST, { email: app.pwResetInfo.email, password: app.pwResetInfo.password })
        .then(() => {
          this.$router.push("/");
        })
        .catch(e => {
          this.messages = e.data.messages;
        });
    },
  },

  mounted() {
    const app = this;
    axios
    .get("/api/password/find/" + this.$route.params.token)
    .then(response => {
        app.pwResetInfo = response.data;
        app.loaded = true;
    })
    .catch(err => {
        console.log(err);
    });
  },
  computed: {
    passValid() {
      if (this.password.length == 0) return null;
      if (this.password.length < 6) {
        this.messages.password = ["Password must be at least 6 characters"];
        return false;
      } else {
        return true;
      }
    },
    passMatch() {
      if (this.confirmPassword == "") {
        return null;
      } else if (this.password == this.confirmPassword) {
        return true;
      } else {
        this.messages.confirmPassword[0] = "Passwords must match";
        return false;
      }
    },
    nameValid() {
      if (this.name == "") {
        return null;
      } else if (!/\s/.test(this.name)) {
        this.messages.name[0] = "Please enter first and last name";
        return false;
      } else if (this.name.length < 3) {
        this.messages.name[0] = "Name must be at least 3 characters";
        return false;
      } else {
        return true;
      }
    },
    ...mapGetters(["isProfileLoaded"]),
    ...mapState({
      authLoading: authState => authState.auth.status === "loading"
    })
  }
};
</script>