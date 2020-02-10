<template>
  <div class='card'>
    <div class='card-header'>Register</div>
    <div class='card-body'>
      <b-form>
        <EmailField v-on:email-update="getEmail" :messages="messages.email" ></EmailField>
        
        <b-form-group label='Name' label-for='name'>
          <b-form-input id='name' type='text' class='form-control' name='name' required autocomplete='name' v-model='name' />
          <b-form-invalid-feedback force-show v-if='messages.name'>{{messages.name[0]}}</b-form-invalid-feedback>
        </b-form-group>

        <b-form-group label='Password' label-for='password'>
          <b-form-input id='password' type='password' class='form-control' name='password' required autocomplete='current-password' v-model='password' @input="passMatch" />
          <b-form-invalid-feedback force-show v-if='messages.password[0]'>{{messages.password[0]}}</b-form-invalid-feedback>
        </b-form-group>

        <b-form-group label='Confirm Password' label-for='confirm_password'>
          <b-form-input id='confirm_password' type='password' class='form-control' name='confirmPassword' required  v-model='confirmPassword' @input="passMatch"/>
          <b-form-invalid-feedback force-show v-if='messages.confirmPassword'>{{messages.confirmPassword[0]}}</b-form-invalid-feedback>
        </b-form-group>

        <b-form-group label label-for='login' class='mb-0'>
          <div class='col-md-4'>
            <b-button v-on:click='register()' v-if='!authLoading||registering' class='btn btn-primary'>Register</b-button>
            <b-button v-else class='btn btn-info'>
                <b-spinner small label='Loading...'></b-spinner>
            </b-button>
          </div>
        </b-form-group>
      </b-form>
    </div>
  </div>
</template>
<script>
import axios from "axios";
import { FormPlugin, ButtonPlugin,SpinnerPlugin } from "bootstrap-vue";
import EmailField from "../../components/EmailField";
import { AUTH_REQUEST } from "../../store/actions/auth";
import Vue from "vue";
import { mapGetters, mapState } from "vuex";
Vue.use(ButtonPlugin);
Vue.use(FormPlugin);
Vue.use(SpinnerPlugin);
export default {
  name: "Register",
  components: {
    EmailField
  },
  data() {
    return {
      name: "",
      email: "",
      password: "",
      confirmPassword: "",
      registering:false,
      messages: {
        name: [],
        email: [],
        password: [],
        confirmPassword:[]
      }
    };
  },
  methods: {
    passMatch(){
        this.password==this.confirmPassword ? this.messages.confirmPassword[0]="":this.messages.confirmPassword[0]="Passwords must match";
      },
    getEmail(email) {
      this.email = email;
    },login() {
      this.$store
        .dispatch(AUTH_REQUEST, { email: this.email, password: this.password })
        .then(() => {
          this.$router.push("/");
        })
        .catch(e => {
          this.messages = e.data.messages;
        });
    },
    register() {
      this.registering=true;
      if(this.messages.confirmPassword){
      let app=this;
      axios
        .post("/api/register", {
          name: app.name,
          email: app.email,
          password: app.password
        })
        .then(function(response) {
          app.registering=false;
          app.login();
        })
        .catch(function(error) {
          app.registering=false;
          app.messages=error.response.data;
          
        });
        app.registering=false;
    }}
  },
  computed: {
      ...mapGetters(["isProfileLoaded"]),
      ...mapState({
        authLoading: authState => authState.auth.status === "loading"
      })
    }
  }

</script>