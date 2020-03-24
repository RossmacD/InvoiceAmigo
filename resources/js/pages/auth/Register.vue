<template>
  <div class="morrocan-bg fillScreen">
    <b-container fluid>
      <b-row>
        <b-col  md="8"  offset-md="2" lg="6" offset-lg="3"	class="px-3">
          <div class='card'>
            <div class='card-body'>
              <div>
                 <b-button to="/login" variant="outline-dark" class="float-right">Login</b-button>
                <h4>Register</h4>
              </div>
              <hr>
              <b-form>
                <EmailField v-on:email-update='getEmail' :messages='messages.email'></EmailField>

                <b-form-group label='Name' label-for='name'>
                  <b-form-input id='name' type='text' class='form-control' name='name' required autocomplete='name' v-model='name' :state='nameValid' />
                  <b-form-invalid-feedback v-if='messages.name'>{{messages.name[0]}}</b-form-invalid-feedback>
                </b-form-group>
        
                <b-form-group label='Password' label-for='password'>
                  <b-form-input id='password' type='password' class='form-control' name='password' required autocomplete='current-password' v-model='password' :state='passValid' />
                  <b-form-invalid-feedback v-if='messages.password[0]'>{{messages.password[0]}}</b-form-invalid-feedback>
                </b-form-group>
        
                <b-form-group label='Confirm Password' label-for='confirm_password'>
                  <b-form-input id='confirm_password' type='password' class='form-control' name='confirmPassword' required v-model='confirmPassword' :state='passMatch' />
                  <b-form-invalid-feedback v-if='messages.confirmPassword'>{{messages.confirmPassword[0]}}</b-form-invalid-feedback>
                </b-form-group>
        
                <hr>
                <b-form-group label label-for='login' class='mb-0'>
               
                    <b-button v-on:click='register()' block v-if='!authLoading||registering' class='btn btn-primary'>Register</b-button>
                    <b-button v-else class='btn btn-info'>
                      <b-spinner small label='Loading...'></b-spinner>
                    </b-button>
              
                </b-form-group>
              </b-form>
            </div>
          </div>
        </b-col>
      </b-row>
    </b-container>
  </div>
</template>
<script>
import axios from "axios";
import { FormPlugin, ButtonPlugin, SpinnerPlugin } from "bootstrap-vue";
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
      registering: false,
      messages: {
        name: [],
        email: [],
        password: [],
        confirmPassword: []
      }
    };
  },
  methods: {
    getEmail(email) {
      this.email = email;
    },
    login() {
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
      this.registering = true;
      if (this.messages.confirmPassword) {
        let app = this;
        axios
          .post("/api/register", {
            name: app.name,
            email: app.email,
            password: app.password
          })
          .then(function(response) {
            app.registering = false;
            app.login();
          })
          .catch(function(error) {
            app.registering = false;
            app.messages = error.response.data;
          });
        app.registering = false;
      }
    }
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