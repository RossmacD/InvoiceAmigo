<template>
    <div>
    <LoadingPage v-if="profileLoading"></LoadingPage>
    <div v-else-if='profileLoaded'>
      <h1>Account Settings</h1>
      <h4>Name:</h4> <p>{{name}}</p>
      <span>Business Account</span><b-form-checkbox v-model="checked"  :disabled="checked" name="business-account-switch" switch>
      </b-form-checkbox>

      <div v-if="checked">
        <hr>
        <h2>Business Settings</h2>
        
          <b-form>
            <b-row>
              <b-col md='3'>
                <b-form-group label='Business Name' label-for='business_name'>
                  <b-form-input aria-describedby='input-live-feedback' id='business_name' type='text' name='business_name' required autocomplete='business_name' autofocus v-model='business.business_name'></b-form-input>
                  <b-form-invalid-feedback v-if='messages.business.business_name' force-show>{{messages.business.business_name[0]}}</b-form-invalid-feedback>
                  <!-- <b-form-invalid-feedback id='input-live-feedback'>Enter at least 5 letters</b-form-invalid-feedback> -->
                </b-form-group>
              </b-col>
              <b-col md='3'>
                <b-form-group label='Website' label-for='website'>
                  <b-form-input id='website' type='text' name='website' required autocomplete='website' autofocus v-model='business.website'></b-form-input>
                  <b-form-invalid-feedback v-if='messages.business.website' force-show>{{messages.business.website[0]}}</b-form-invalid-feedback>
                </b-form-group>
              </b-col>
            </b-row>
            <b-row>
              <b-col md='6'>
                <b-form-group label='Address' label-for='address'>
                  <b-form-input id='address' type='text' name='address' required autocomplete='address' autofocus v-model='business.address'></b-form-input>
                  <b-form-invalid-feedback v-if='messages.business.address' force-show>{{messages.business.address[0]}}</b-form-invalid-feedback>
                </b-form-group>
              </b-col>
            </b-row>
            <b-row>
              <b-col md='3'>
                <b-form-group label='Country' label-for='country'>
                  <b-form-input id='country' type='text' name='country' required autocomplete='country' autofocus v-model='business.country'></b-form-input>
                  <b-form-invalid-feedback v-if='messages.business.country' force-show>{{messages.business.country[0]}}</b-form-invalid-feedback>
                </b-form-group>
              </b-col>
              <b-col md='3'>
                <b-form-group label='Postcode' label-for='postcode'>
                  <b-form-input id='postcode' type='text' name='postcode' required autocomplete='postcode' autofocus v-model='business.postcode'></b-form-input>
                  <b-form-invalid-feedback v-if='messages.business.postcode' force-show>{{messages.business.postcode[0]}}</b-form-invalid-feedback>
                </b-form-group>
              </b-col>
            </b-row>
            <b-form-group class='mb-0'>
              <div>
                <b-button v-on:click='submit()' v-if='!businessSubmitLoading' class='btn btn-primary'><span v-if='editing'>Update</span>
                  <span v-else>Save</span>
                </b-button>
                <b-button v-else variant='info'>
                  <b-spinner small label='Loading...'></b-spinner>
                </b-button>
              </div>
            </b-form-group>
          </b-form>

          <h2>cPanel Settings</h2>
          <p>These settings are for integration with a cPanel WHM/reseller account.</p>

      </div>
    </div>
    <ErrorPage v-else></ErrorPage>
  </div>
</template>

<script>
import Vue from "vue";
import { mapGetters, mapState } from "vuex";
import { CardPlugin, ButtonPlugin, LayoutPlugin } from "bootstrap-vue";
import ErrorPage from "../components/ErrorPage";
import LoadingPage from "../components/LoadingPage";
Vue.use(CardPlugin);
Vue.use(ButtonPlugin);
Vue.use(LayoutPlugin);

export default {
  name: "Settings",
  components: {
    LoadingPage
  },
  data() {
    return {
      checked: false,
      businessSubmitLoading: false,
      business: {
        business_name: "",
        website: "",
        address: "",
        country: "",
        postcode: ""
      },
      messages: {
        business: {
          business_name: [],
          website: [],
          address: [],
          country: [],
          postcode: []
        }
      },
      editing: false,
      submiting: false
    };
  },
  methods: {
    submit() {
      const app = this;
      app.businessSubmitLoading = true;
      app.submiting = true;
      if (app.isAuthenticated) {
        if (app.editing) {
          axios
            .put("/api/businesses/"+ app.business.id, app.business)
            .then(response => {
              app.businessSubmitLoading = false;
            })
            .catch(err => {console.log(err.response)});
        } else {
          axios
            .post("/api/businesses/", app.business)
            .then(response => {
              // this.$router.push("/settings");
            })
            .catch(err => {});
        }

        app.submiting = false;
      }
    }
  },
  components: {
    ErrorPage,
    LoadingPage
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
    if (app.isBusiness) {
      app.checked = true;
      app.editing = true;
      axios
        .get("/api/businesses")
        .then(response => {
          app.business = response.data.business;
        })
        .catch(err => {
          console.log(err);
        });
    }
  }
};
</script>