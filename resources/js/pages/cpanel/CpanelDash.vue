<template>
  <div>
    <h2 class='mb-4 display-4'>
      cPanel/WHM
    </h2>
    <b-row v-if="!hasCpanel">
      <p>Please set your cPanel/WHM details here to use this feature.</p>
    </b-row>
    <b-row v-else>
      <b-col md="5">
        <b-form>
          <h3>Create cPanel Account</h3>
          <b-row>
            <b-col md="10">
              <hr style="margin-top: 0px;">
            </b-col>
          </b-row>
          <b-row>
            <b-col md="9">
              <b-form-group label="Username" label-for="username">
                <b-form-input
                  aria-describedby="input-live-feedback"
                  id="username"
                  type="text"
                  name="username"
                  required
                  autocomplete="username"
                  v-model="cpanel.username"
                  placeholder="Enter username"
                ></b-form-input>
                <b-form-invalid-feedback
                  v-if="messages.cpanel.username"
                  force-show
                >{{messages.cpanel.username[0]}}</b-form-invalid-feedback>
                <!-- <b-form-invalid-feedback id='input-live-feedback'>Enter at least 5 letters</b-form-invalid-feedback> -->
              </b-form-group>
            </b-col>
          </b-row>
          <b-row>
            <b-col md="9">
              <b-form-group label="Domain" label-for="domain">
                <b-form-input
                  id="domain"
                  type="text"
                  name="domain"
                  required
                  autocomplete="domain"
                  v-model="cpanel.domain"
                  placeholder="Enter domain"
                ></b-form-input>
                <b-form-invalid-feedback
                  v-if="messages.cpanel.domain"
                  force-show
                >{{messages.cpanel.domain[0]}}</b-form-invalid-feedback>
              </b-form-group>
            </b-col>
          </b-row>
          <b-row>
            <b-col md="9">
              <b-form-group label="Password" label-for="password">
                <b-form-input
                  id="password"
                  type="password"
                  name="password"
                  required
                  autocomplete="password"
                  v-model="cpanel.password"
                  placeholder="Enter password"
                ></b-form-input>
                <b-form-invalid-feedback
                  v-if="messages.cpanel.password"
                  force-show
                >{{messages.cpanel.password[0]}}</b-form-invalid-feedback>
              </b-form-group>
            </b-col>
          </b-row>
          <b-row>
            <b-col md="9">
              <b-form-group label="Email" label-for="email">
                <b-form-input
                  id="email"
                  type="email"
                  name="email"
                  required
                  autocomplete="email"
                  v-model="cpanel.email"
                  placeholder="Enter email"
                ></b-form-input>
                <b-form-invalid-feedback
                  v-if="messages.cpanel.email"
                  force-show
                >{{messages.cpanel.email[0]}}</b-form-invalid-feedback>
              </b-form-group>
            </b-col>
          </b-row>
          <b-row>
            <b-col md="9">
              <b-form-group label="Plan" label-for="plan">
                <b-form-select
                plain
                required
                v-model="cpanel.plan"
                aria-describedby="input-plan-live-feedback"
                >
                <template v-slot:first>
                  <b-form-select-option :value="null">Select a plan</b-form-select-option>
                </template>
                <!-- <b-form-select-option
                  v-for="course in courses"
                  :key="course.id"
                  :value="course.id"
                >{{ course.title }}</b-form-select-option> -->

                <!-- <b-form-input
                  id="plan"
                  type="text"
                  name="plan"
                  required
                  autocomplete="plan"
                  v-model="cpanel.plan"
                ></b-form-input> -->
                </b-form-select>
                
                <b-form-invalid-feedback
                  v-if="messages.cpanel.plan"
                  force-show
                >{{messages.cpanel.plan[0]}}</b-form-invalid-feedback>
              </b-form-group>
            </b-col>
          </b-row>
          <b-form-group class="mb-0">
            <div>
              <b-button v-on:click="submit()" v-if="!cpanelSubmitLoading" class="btn btn-primary">
                <span v-if="editing">Update</span>
                <span v-else>Create</span>
              </b-button>
              <b-button v-else variant="info">
                <b-spinner small label="Loading..."></b-spinner>
              </b-button>
            </div>
          </b-form-group>
        </b-form>
      </b-col>
      <b-col md="7">
        <h3>cPanel Accounts</h3>
        <LoadingPage v-if="loadingAccts"></LoadingPage>
        <EmptyIndex
          :fields="fields"
          indexType="account"
          v-bind:button="false"
          v-else-if="accounts.length===0"
        ></EmptyIndex>
        <b-table
          v-else
          hover
          small
          :fields="fields"
          :items="accounts"
          :tbody-tr-class="accountStatus"
          responsive
          sort-by="user"
        >
          <!-- Actions slot -->
          <template class="text-nowrap" v-slot:cell(domain)="data">
            <div class="text-nowrap">
              <a :href="'http://' + data.item.domain">{{data.item.domain}}</a>
            </div>
          </template>

          <!-- Storage slot -->
          <template class="text-nowrap" v-slot:cell(storage)="data">
            <div class="text-nowrap">
              <b-progress
                :value="data.item.diskUsed"
                :max="data.item.diskLimit"
                animated
                v-b-tooltip.hover
                :title="(data.item.diskUsed/1000) + ' GB / ' + (data.item.diskLimit/1024) + ' GB'"
              ></b-progress>
            </div>
          </template>

          <!-- Actions slot -->
          <template class="text-nowrap" v-slot:cell(actions)="data">
            <div class="text-nowrap">
              <b-spinner v-if="unsuspending && data.item.uid===accountId" small label="Loading..."></b-spinner>
              <b-icon
                v-else-if="data.item.suspensionReason=='not suspended'"
                @click="showSuspendAcctModal(data.item.user, data.item.uid)"
                icon="pause-fill"
                v-b-tooltip.hover
                title="Suspend"
              ></b-icon>
              <b-icon
                v-else
                @click="unsuspendAcct(data.item.user, data.item.uid)"
                icon="play-fill"
                v-b-tooltip.hover
                title="Unsuspend"
              ></b-icon>
              <b-icon
                @click="showTerminateAcctModal(data.item.user, data.item.uid)"
                icon="x-octagon-fill"
                v-b-tooltip.hover
                title="Terminate"
              ></b-icon>
            </div>
          </template>
        </b-table>
      </b-col>
    </b-row>
    <b-modal
      ref="suspension-modal"
      id="suspension-modal"
      hide-footer
      class="modal-backdrop"
      modal-backdrop
      title="Suspend Account"
    >
      <p
        class="d-block"
      >This will temporarily suspend all access to this cPanel account and website.</p>
      <p>Please enter a suspension reason below:</p>
      <b-form-input
        aria-describedby="input-live-feedback"
        id="reason"
        type="text"
        name="reason"
        required
        autocomplete="reason"
        v-model="suspensionPayload.suspendReason"
      ></b-form-input>
      <br />
      <b-button @click="hideModals">Cancel</b-button>
      <b-button @click="suspendAcct()" class="btn btn-danger" v-if="!suspending">Suspend</b-button>
      <b-button class="btn btn-danger disabled" v-else>
        <b-spinner small label="Loading..."></b-spinner>
      </b-button>
    </b-modal>

    <b-modal
      ref="termination-modal"
      id="termination-modal"
      hide-footer
      class="modal-backdrop"
      modal-backdrop
      title="Terminate Account"
    >
      <p class="d-block">
        This will
        <strong>permanently</strong>
        delete cPanel account {{terminationPayload.userToTerminate}} and all its files! This action is irreversible.
      </p>
      <p>Are you sure you want to continue?</p>
      <b-button @click="hideModals">Cancel</b-button>
      <b-button @click="terminateAcct()" class="btn btn-danger" v-if="!terminating">Terminate</b-button>
      <b-button class="btn btn-danger disabled" v-else>
        <b-spinner small label="Loading..."></b-spinner>
      </b-button>
    </b-modal>
  </div>
</template>

<script>
import Vue from "vue";
import { mapGetters, mapState } from "vuex";
import { CardPlugin, ButtonPlugin, LayoutPlugin } from "bootstrap-vue";
import ErrorPage from "../../components/ErrorPage";
import LoadingPage from "../../components/LoadingPage";
import EmptyIndex from "../../components/EmptyIndex";

Vue.use(CardPlugin);
Vue.use(ButtonPlugin);
Vue.use(LayoutPlugin);

export default {
  name: "CpanelDash",
  components: {
    ErrorPage,
    LoadingPage,
    EmptyIndex
  },
  data() {
    return {
      checked: false,
      accounts: [],
      // fields: ["user", "domain", "storage", "actions"],
      fields: [
          { key: 'user', sortable: true },
          { key: 'domain', sortable: true },
          { key: 'storage', sortable: false },
          { key: 'actions', sortable: false }
      ],
      cpanelSubmitLoading: false,
      cpanel: {
        username: "",
        domain: "",
        password: "",
        plan: null
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
      submiting: false,
      loadingAccts: false,
      suspending: false,
      unsuspending: false,
      terminating: false,
      accountId: '',
      suspensionPayload: {
        userToSuspend: "",
        suspendReason: ""
      },
      terminationPayload: {
        userToTerminate: ""
      }
    };
  },
    computed: {
    ...mapGetters(["getProfile", "isAuthenticated", "isProfileLoaded", "isBusiness", "hasCpanel"]),
    ...mapState({
      authLoading: state => state.auth.status === "loading",
      name: state => `${state.user.profile.name}`,
      profileLoading: state => state.user.status === "loading",
      profileLoaded: state=> state.user.status === "success"
    })
  },
  methods: {
      accountStatus(item, type) {
        if (!item || type !== 'row') return
        if (item.suspensionReason !== 'not suspended'){
          return 'table-warning'
        } else {
          return 'table-transition'
        }
      },
    showSuspendAcctModal(account_user, id){
      const app = this;
      app.accountId = id;
      app.suspensionPayload.userToSuspend = account_user;
      app.$bvModal.show("suspension-modal");

    },    
    showTerminateAcctModal(account_user, id){
      const app = this;
      app.accountId = id;
      app.terminationPayload.userToTerminate = account_user;
      app.$bvModal.show("termination-modal");

    },
    suspendAcct() {
      const app = this;
      app.suspending = true;

      axios
        .post("/api/cpanel/suspend/", app.suspensionPayload)
        .then(response => {
          app.suspending = false;
          app.accounts.filter(account => account.uid == app.accountId)[0].suspensionReason = app.suspensionPayload.suspendReason;

          // app.accounts[app.accountIndex].suspensionReason = reason;
          app.hideModals();
          app.makeSuccessToast(1);
          app.suspensionPayload.suspendReason = '';
          app.suspensionPayload.userToSuspend = '';

        })
        .catch(err => {
          console.log(err);
          app.suspending = false;
        });

    },
    unsuspendAcct(account_user, id) {
      const app = this;
      app.unsuspending = true;
      app.suspensionPayload.userToSuspend = account_user;
      app.accountId = id;

      axios
        .post("/api/cpanel/unsuspend/", app.suspensionPayload)
        .then(response => {
          app.unsuspending = false;
          app.accounts.filter(account => account.uid == app.accountId)[0].suspensionReason = 'not suspended';
          app.makeSuccessToast(2);
          app.suspensionPayload.userToSuspend = '';

        })
        .catch(err => {
          console.log(err);
          app.unsuspending = false;
        });
    },
    terminateAcct() {
      const app = this;
      app.terminating = true;

      axios
        .post("/api/cpanel/terminate/", app.terminationPayload)
        .then(response => {
          app.terminating = false;
          app.hideModals();
          const deleteAccount = app.accounts.filter((account, index) => {
            account.index = index;
            return account.uid == app.accountId;
          })
          app.$delete(app.accounts, deleteAccount[0].index);
          app.makeSuccessToast(3);
          app.terminationPayload.userToTerminate = '';

        })
        .catch(err => {
          console.log(err);
          app.terminating = false;
        });
    },
    hideModals() {
      const app = this;
      app.$bvModal.hide("suspension-modal");
      app.$bvModal.hide("termination-modal");
    },
    makeSuccessToast(type, append = false) {
      const app = this;
      let msg = '';
      switch (type) {
        case 1:
          msg=app.suspensionPayload.userToSuspend + " suspended"
          break;
        case 2:
          msg=app.suspensionPayload.userToSuspend + " unsuspended"
          break;
        case 3:
          msg=app.terminationPayload.userToTerminate + " terminated"
          break;
        default:
          break;
      }
      this.$bvToast.toast(`Account ` + msg + ` successfully`, {
        title: "Success",
        variant: "success",
        toaster: "b-toaster-bottom-right",
        autoHideDelay: 3000,
        appendToast: append
      });
      this.$store.dispatch("ADD_NOTIFICATIONS", {id:1,message:`Account ${msg} successfully`});
    },
  },
  mounted() {
    const app = this;
    if (true) {
      app.loadingAccts = true;
      axios
        .get("/api/cpanel")
        .then(response => {
          app.accounts = response.data.accounts.accounts;
          app.loadingAccts = false;
        })
        .catch(err => {
          console.log(err);
          app.loadingAccts = false;
        });
    }
  }
}
</script>

<style>
/* .table-transition{
  transition: all 0.15s ease-in-out;
} */
</style>