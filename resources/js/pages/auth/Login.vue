<template>
  <div class="morrocan-bg fillScreen ">
    <div class="marginScreen">
      <b-container fluid >
        <b-row>
          <b-col md="8"  offset-md="2" lg="6" offset-lg="3"	class="px-3">
            <div class='card'>
              <div class='card-body'>
                <div>
                   <b-button to="/register" variant="outline-dark" class="float-right">Register</b-button>
                  <h4>Login</h4>
                </div>
                <hr>
                <b-form>
                  <EmailField v-on:email-update="getEmail"  :messages="messages.email" ></EmailField>
                 
                  <b-form-group label='Password' label-for='password'>

                    <b-form-input id='password' type='password' class='form-control' name='password' required autocomplete='current-password' v-model='password' @keydown.enter.native='login()' />
                    
                    <b-form-invalid-feedback force-show v-if='messages.password'>{{messages.password[0]}}</b-form-invalid-feedback>
                    <small>
                    <b-link>Forgot Password?</b-link>
                  </small>
                  </b-form-group>
           
                  <!-- <b-form-group label label-for='remember'>
                    <b-form-checkbox id='remember' name='remember' value='remember'>Remember Me</b-form-checkbox>
                  </b-form-group> -->
          
                  <b-form-group label label-for='login' class='mb-0'>
                    <b-button id='login' v-on:click='login()' block v-if='!authLoading'>Login</b-button>
                    <b-button v-else >
                      <b-spinner small label='Loading...'></b-spinner>
                    </b-button>
                    <!--                                
                                          <a class="btn btn-link" href="{{ route('password.request') }}">
                                              Forgot your password
                    </a>-->
                  </b-form-group>
          
                  <hr>
                  <b-button href="/redirect" block variant="dark" v-if='!authLoading'>Login with Google</b-button>
                </b-form>
              </div>
            </div>
          </b-col>
        </b-row>
      </b-container>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import { FormPlugin, ButtonPlugin, SpinnerPlugin,LinkPlugin } from "bootstrap-vue";
import Vue from "vue";
import { mapGetters, mapState } from "vuex";
import { AUTH_REQUEST } from "../../store/actions/auth";
import EmailField from "../../components/EmailField";
Vue.use(SpinnerPlugin);
Vue.use(ButtonPlugin);
Vue.use(FormPlugin);
Vue.use(LinkPlugin)

export default {
  name: "Login",
  components: {
    EmailField
  },
  data() {
    return {
      name: "",
      email: "",
      password: "",
      messages: {
        email: [],
        password: []
      }
    };
  },
  methods: {
    getEmail(email){
      this.email=email
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
    }
  },
  computed: {
    ...mapGetters(["isProfileLoaded"]),
    ...mapState({
      authLoading: state => state.auth.status === "loading"
    })
  }
};
</script>