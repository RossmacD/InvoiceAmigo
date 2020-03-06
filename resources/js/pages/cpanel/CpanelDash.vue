<template>
<div>
    <b-row>
        <b-col md="6">
            <h3>cPanel Accounts</h3>
            <b-list-group v-for="account in accounts" v-bind:key="account.user">
                <b-list-group-item>Name: {{account.user}}, Domain: {{account.domain}}</b-list-group-item>
            </b-list-group>
        </b-col>
<b-col md="6">
  <b-form>
      <h3>Create cPanel Account</h3>
            <b-row>
              <b-col md='6'>
                <b-form-group label='Username' label-for='username'>
                  <b-form-input aria-describedby='input-live-feedback' id='username' type='text' name='username' required autocomplete='username' autofocus v-model='cpanel.username'></b-form-input>
                  <b-form-invalid-feedback v-if='messages.cpanel.username' force-show>{{messages.cpanel.username[0]}}</b-form-invalid-feedback>
                  <!-- <b-form-invalid-feedback id='input-live-feedback'>Enter at least 5 letters</b-form-invalid-feedback> -->
                </b-form-group>
              </b-col>
            </b-row>
            <b-row>
              <b-col md='6'>
                <b-form-group label='Domain' label-for='domain'>
                  <b-form-input id='domain' type='text' name='domain' required autocomplete='domain' autofocus v-model='cpanel.domain'></b-form-input>
                  <b-form-invalid-feedback v-if='messages.cpanel.domain' force-show>{{messages.cpanel.domain[0]}}</b-form-invalid-feedback>
                </b-form-group>
              </b-col>
            </b-row>
            <b-row>
              <b-col md='6'>
                <b-form-group label='Plan' label-for='plan'>
                  <b-form-input id='plan' type='text' name='plan' required autocomplete='plan' autofocus v-model='cpanel.plan'></b-form-input>
                  <b-form-invalid-feedback v-if='messages.cpanel.plan' force-show>{{messages.cpanel.plan[0]}}</b-form-invalid-feedback>
                </b-form-group>
              </b-col>
            </b-row>
            <b-row>
              <b-col md='6'>
                <b-form-group label='Password' label-for='password'>
                  <b-form-input id='password' type='password' name='password' required autocomplete='password' autofocus v-model='cpanel.password'></b-form-input>
                  <b-form-invalid-feedback v-if='messages.cpanel.password' force-show>{{messages.cpanel.password[0]}}</b-form-invalid-feedback>
                </b-form-group>
              </b-col>
            </b-row>
            <b-form-group class='mb-0'>
              <div>
                <b-button v-on:click='submit()' v-if='!cpanelSubmitLoading' class='btn btn-primary'><span v-if='editing'>Update</span>
                  <span v-else>Create</span>
                </b-button>
                <b-button v-else variant='info'>
                  <b-spinner small label='Loading...'></b-spinner>
                </b-button>
              </div>
            </b-form-group>
    </b-form>
</b-col>
</b-row>
</div>
</template>

<script>
import Vue from "vue";
import { mapGetters, mapState } from "vuex";
import { CardPlugin, ButtonPlugin, LayoutPlugin } from "bootstrap-vue";
import ErrorPage from "../../components/ErrorPage";
import LoadingPage from "../../components/LoadingPage";
Vue.use(CardPlugin);
Vue.use(ButtonPlugin);
Vue.use(LayoutPlugin);

export default {
  name: "CpanelDash",
  components: {
    ErrorPage,
    LoadingPage
  },
  data() {
    return {
      checked: false,
      accounts:[],
      cpanelSubmitLodading: false,
      cpanel: {
        username: "",
        domain: "",
        password: "",
        plan: ""
      },
      messages: {
        cpanel: {
          username: [],
          domain: [],
          password: [],
          plan: []
        }
      },
      editing: false,
      submiting: false
    };
  },
    computed: {
    ...mapGetters(["getProfile", "isAuthenticated", "isProfileLoaded", "isBusiness"]),
    ...mapState({
      authLoading: state => state.auth.status === "loading",
      name: state => `${state.user.profile.name}`,
      profileLoading: state => state.user.status === "loading",
      profileLoaded: state=> state.user.status === "success"
    })
  },
  mounted() {
    const app = this;
    if (true) {
      app.checked = true;
      app.editing = true;
      axios
        .get("/api/cpanel")
        .then(response => {
          app.accounts = response.data.accounts.accounts;
          console.log("test: "+app.accounts);
        })
        .catch(err => {
          console.log(err);
        });
    }
  }
}
</script>

<style>

</style>