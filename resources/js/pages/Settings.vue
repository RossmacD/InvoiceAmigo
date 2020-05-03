<template>
  <div>
    <LoadingPage v-if="profileLoading"></LoadingPage>
    <div v-else-if="profileLoaded">
      <h2 class='mb-4 display-4'>
      Account Settings</h2>
      <h4>Name:</h4>
      <p>{{name}}</p>
      <span>Business Account</span>
      <b-form-checkbox v-model="checked" :disabled="checked" name="business-account-switch" switch></b-form-checkbox>

      <div v-if="checked">
        <hr />

        <b-col md="6">
          <b-card no-body>
            <b-card-header
              v-b-toggle.business-settings-collapse
              header-tag="header"
              class="p-1"
              role="tab"
            >
              <b-button variant="btn" href="#">
                <h2>Business Settings</h2>
              </b-button>
              <b-button class="float-right p-3" variant="btn">
                <b-icon icon="chevron-down"></b-icon>
              </b-button>
            </b-card-header>
            <b-collapse
              id="business-settings-collapse"
              visible
              accordion="business-accordion"
              role="tabpanel"
            >
              <b-card-body>
                <b-form>
                  <b-row>
                    <b-col md="6">
                      <b-form-group label="Business Name" label-for="business_name">
                        <b-form-input
                          aria-describedby="input-live-feedback"
                          id="business_name"
                          type="text"
                          name="business_name"
                          required
                          autocomplete="business_name"
                          v-model="business.business_name"
                        ></b-form-input>
                        <b-form-invalid-feedback
                          v-if="messages.business.business_name"
                          force-show
                        >{{messages.business.business_name[0]}}</b-form-invalid-feedback>
                        <!-- <b-form-invalid-feedback id='input-live-feedback'>Enter at least 5 letters</b-form-invalid-feedback> -->
                      </b-form-group>
                    </b-col>
                    <b-col md="6">
                      <b-form-group label="Website" label-for="website">
                        <b-form-input
                          id="website"
                          type="text"
                          name="website"
                          required
                          autocomplete="website"
                          v-model="business.website"
                        ></b-form-input>
                        <b-form-invalid-feedback
                          v-if="messages.business.website"
                          force-show
                        >{{messages.business.website[0]}}</b-form-invalid-feedback>
                      </b-form-group>
                    </b-col>
                  </b-row>
                  <b-row>
                    <b-col md="12">
                      <b-form-group label="Address" label-for="address">
                        <b-form-input
                          id="address"
                          type="text"
                          name="address"
                          required
                          autocomplete="address"
                          v-model="business.address"
                        ></b-form-input>
                        <b-form-invalid-feedback
                          v-if="messages.business.address"
                          force-show
                        >{{messages.business.address[0]}}</b-form-invalid-feedback>
                      </b-form-group>
                    </b-col>
                  </b-row>
                  <b-row>
                    <b-col md="6">
                      <b-form-group label="Country" label-for="country">
                        <b-form-input
                          id="country"
                          type="text"
                          name="country"
                          required
                          autocomplete="country"
                          v-model="business.country"
                        ></b-form-input>
                        <b-form-invalid-feedback
                          v-if="messages.business.country"
                          force-show
                        >{{messages.business.country[0]}}</b-form-invalid-feedback>
                      </b-form-group>
                    </b-col>
                    <b-col md="6">
                      <b-form-group label="Postcode" label-for="postcode">
                        <b-form-input
                          id="postcode"
                          type="text"
                          name="postcode"
                          required
                          autocomplete="postcode"
                          v-model="business.postcode"
                        ></b-form-input>
                        <b-form-invalid-feedback
                          v-if="messages.business.postcode"
                          force-show
                        >{{messages.business.postcode[0]}}</b-form-invalid-feedback>
                      </b-form-group>
                    </b-col>
                  </b-row>
                  <IbanCollect v-if="!bEditing" />
                  <b-form-group class="mb-0">
                    <div>
                      <b-button
                        v-on:click="submit()"
                        v-if="!businessSubmitLoading"
                        class="btn btn-primary"
                      >
                        <span v-if="bEditing">Update</span>
                        <span v-else>Save</span>
                      </b-button>
                      <b-button v-else variant="info">
                        <b-spinner small label="Loading..."></b-spinner>
                      </b-button>
                    </div>
                  </b-form-group>
                </b-form>
              </b-card-body>
            </b-collapse>
          </b-card>
        </b-col>

        <hr />
        <b-col md="6">
          <b-card no-body class="mb-1">
            <b-card-header
              v-b-toggle.cpanel-settings-collapse
              header-tag="header"
              class="p-1"
              role="tab"
            >
              <b-button variant="btn" href="#">
                <h2>cPanel/WHM Settings</h2>
              </b-button>
              <b-button class="float-right p-3" variant="btn">
                <b-icon icon="chevron-down"></b-icon>
              </b-button>
            </b-card-header>
            <b-collapse :visible="cEditing" id="cpanel-settings-collapse" accordion="cpanel-accordion" role="tabpanel">
              <b-card-body>
                <!-- <b-card-text>I start opened because <code>visible</code> is <code>true</code></b-card-text> -->
                <!-- <b-card-text>{{ text }}</b-card-text> -->

                <p>These settings are for integration with a cPanel WHM/reseller account.</p>
                <b-form>
                  <b-row>
                    <b-col md="12">
                      <b-form-group label="WHM Username" label-for="whm_username">
                        <b-form-input
                          aria-describedby="input-live-feedback"
                          id="whm_username"
                          type="text"
                          name="whm_username"
                          required
                          autocomplete="whm_username"
                          v-model="cpanel.whm_username"
                        ></b-form-input>
                        <b-form-invalid-feedback
                          v-if="messages.cpanel.whm_username"
                          force-show
                        >{{messages.cpanel.whm_username[0]}}</b-form-invalid-feedback>
                        <!-- <b-form-invalid-feedback id='input-live-feedback'>Enter at least 5 letters</b-form-invalid-feedback> -->
                      </b-form-group>
                    </b-col>
                  </b-row>
                  <b-row>
                    <b-col md="12">
                      <b-form-group label="API token" label-for="api_token">
                        <b-form-input
                          id="api_token"
                          type="text"
                          name="api_token"
                          required
                          autocomplete="api_token"
                          v-model="cpanel.api_token"
                        ></b-form-input>
                        <b-form-invalid-feedback
                          v-if="messages.cpanel.api_token"
                          force-show
                        >{{messages.cpanel.api_token[0]}}</b-form-invalid-feedback>
                      </b-form-group>
                    </b-col>
                  </b-row>
                  <b-row>
                    <b-col md="6">
                      <b-form-group label="Hostname" label-for="hostname">
                        <b-form-input
                          id="hostname"
                          type="text"
                          name="hostname"
                          required
                          autocomplete="hostname"
                          v-model="cpanel.hostname"
                        ></b-form-input>
                        <b-form-invalid-feedback
                          v-if="messages.cpanel.hostname"
                          force-show
                        >{{messages.cpanel.hostname[0]}}</b-form-invalid-feedback>
                      </b-form-group>
                    </b-col>
                    <b-col md="6">
                      <b-form-group label="Port" label-for="port">
                        <b-form-input
                          id="port"
                          type="text"
                          name="port"
                          required
                          autocomplete="port"
                          v-model="cpanel.port"
                        ></b-form-input>
                        <b-form-invalid-feedback
                          v-if="messages.cpanel.port"
                          force-show
                        >{{messages.cpanel.port[0]}}</b-form-invalid-feedback>
                      </b-form-group>
                    </b-col>
                  </b-row>
                  <b-form-group class="mb-0">
                    <div>
                      <b-button
                        v-on:click="submitCpanel()"
                        v-if="!cpanelSubmitLoading"
                        class="btn btn-primary"
                      >
                        <span v-if="cEditing">Update</span>
                        <span v-else>Set</span>
                      </b-button>
                      <b-button v-else variant="info">
                        <b-spinner small label="Loading..."></b-spinner>
                      </b-button>
                    </div>
                  </b-form-group>
                </b-form>
              </b-card-body>
            </b-collapse>
          </b-card>
        </b-col>
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
import IbanCollect from "../components/IbanCollect";
Vue.use(CardPlugin);
Vue.use(ButtonPlugin);
Vue.use(LayoutPlugin);

export default {
  name: "Settings",
  components: {
    ErrorPage,
    LoadingPage,
    IbanCollect
  },
  data() {
    return {
      checked: false,
      businessSubmitLoading: false,
      cpanelSubmitLoading: false,
      business: {
        business_name: "",
        website: "",
        address: "",
        country: "",
        postcode: ""
      },
      cpanel: {
        whm_username: "",
        api_token: "",
        hostname: "",
        port: ""
      },
      messages: {
        business: {
          business_name: [],
          website: [],
          address: [],
          country: [],
          postcode: []
        },
        cpanel: {
          whm_username: [],
          api_token: [],
          hostname: [],
          port: []
        }
      },
      bEditing: false,
      cEditing: false,
      bSubmitting: false,
      cSubmitting: false
    };
  },
  methods: {
    submit() {
      const app = this;
      app.businessSubmitLoading = true;
      app.bSubmitting = true;
      if (app.isAuthenticated) {
        if (app.bEditing) {
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

        app.bSubmitting = false;
      }
    },
    submitCpanel() {
      const app = this;
      console.log(app.cpanel);
      app.cpanelSubmitLoading = true;
      app.cSubmitting = true;
      if (app.isAuthenticated) {
        if (app.cEditing) {
          axios
            .put("/api/cpanel/setdetails/", app.cpanel)
            .then(response => {
              app.cpanelSubmitLoading = false;
            })
            .catch(err => {console.log(err.response)});
        } else {
          axios
            .post("/api/cpanel/setdetails/", app.cpanel)
            .then(response => {
              // this.$router.push("/settings");
              app.cpanelSubmitLoading = false;
              app.cEditing = true; //set cEditing to true as we have now have a cpanel and will be editing it from now on
              this.$store.dispatch('USER_REQUEST'); //make sure getProfile is reloaded to show that the user now has a cPanel account
            })
            .catch(err => {
                app.cpanelSubmitLoading = false;
            });
        }

        app.cSubmitting = false;
      }
    }
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
      app.bEditing = true;

      axios
        .get("/api/businesses")
        .then(response => {
          app.business = response.data.business;
          console.log(app.cpanel);
          if(response.data.business.cpanel){
            app.cpanel = response.data.business.cpanel;
            app.cEditing = true;
          }
        })
        .catch(err => {
          console.log(err);
        });
    }
  }
};
</script>