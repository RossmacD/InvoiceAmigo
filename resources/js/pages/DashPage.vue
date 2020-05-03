<template>
  <div>
    <LoadingPage v-if="profileLoading"></LoadingPage>
    <div v-else-if="profileLoaded">
      <h1 class="display-4">Your Dashboard</h1>
      <h2>Welcome back, {{name}}!</h2>
      <br/>
      <div v-if="!isBusiness">

      </div>
      <div v-else>
      <h3>Business Overview: {{ business.business_name }}</h3>
      <b-row>
        <b-col md="4">
          <b-card>
            <h4>Total Income: </h4><p>€{{ dashInfo.totalIncome }}</p>
          </b-card>
        </b-col>
        <b-col md="4">
          <b-card>
            <h4>Total Outstanding: </h4><p>€{{ dashInfo.totalOutstanding }}</p>
          </b-card>
            
        </b-col>
        <b-col md="4">
          <b-card>
            <h4>Invoices Created: </h4><p>{{ dashInfo.invoicesCreated }}</p>
          </b-card>
        </b-col>
      </b-row>
      </br>
      <b-row>
        <b-col md="8">
           <apexchart type="line" :options="salesDataOptions" :series="salesDataSeries" ></apexchart>

        </b-col>
           <apexchart type="bar" :options="invoiceDataOptions" :series="invoiceDataSeries" ></apexchart>

        <!-- <h5>Unpaid Invoices</h5> -->
      </b-row>
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
import VueApexCharts from 'vue-apexcharts';
Vue.component('apexchart', VueApexCharts)

Vue.use(CardPlugin);
Vue.use(ButtonPlugin);
Vue.use(LayoutPlugin);

export default {
  name: "DashBoard",
  components: {
    ErrorPage,
    LoadingPage,
  },
  data() {
    return {
      dashInfo: {
        unseenCount: 0,
        totalOutstanding: 0,
        invoicesCreated: 0
      },
      unseenCount: 0,
      totalOutstanding: 0,
      invoicesCreated: 0,
    
      invoiceDataOptions: {
        chart: {
          id: 'invoice-data',
        },
        xaxis: {
          categories: ['Paid', 'Unseen'],
        },
        title: {
              text: 'Invoice Data',
              align: 'left'
        },
        plotOptions: {
          bar: {
            horizontal: true,
          }
        }
      },
      invoiceDataSeries: [{
        name: '',
        data: [0,0]
      }],

      salesDataOptions: {
        chart: {
          id: 'sales-data',
        },
        xaxis: {
          categoriesold: ['25 Feb', '26 Feb', '27 Feb', '28 Feb', '29 Feb', '1 Mar', '2 Mar'],
          categories: []
        },
        title: {
              text: 'Weekly Sales',
              align: 'left'
        },
        plotOptions: {
          bar: {
            horizontal: true,
          }
        }
      },
      salesDataSeries: [{
        name: '',
        dataold: [10, 2, 8, 12, 14, 4, 7],
        data: [0,0,0,0,0,0,0]
      }]

    }
  },
  mounted() {
    const app = this;
    axios
    .get("/api/dashboard")
    .then(response => {
        app.dashInfo = response.data;
        this.updateCharts();
        app.loaded = true;
    })
    .catch(err => {
        console.log(err);
    });
    for(let i=6;i>=0;i--){
      app.salesDataOptions.xaxis.categories.push(dateToDayMonth(new Date(new Date().setTime(new Date().getTime() - i * 86400000))));
    }

    function dateToDayMonth(date) {
      var strArray=['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
      var d = date.getDate();
      var m = strArray[date.getMonth()];
      return '' + (d <= 9 ? '0' + d : d) + ' ' + m;
    }
  },
  methods: {
    updateCharts() {
      this.invoiceDataSeries = [{
        data: [this.dashInfo.paidCount,this.dashInfo.unseenCount]
      }]
      this.salesDataSeries = [{
        data: this.dashInfo.weeklySales
      }]
    }
  },
  computed: {
    ...mapGetters(["getProfile", "isAuthenticated", "isProfileLoaded", "isBusiness"]),
    ...mapState({
      authLoading: state => state.auth.status === "loading",
      name: state => `${state.user.profile.name}`,
      business: state => state.user.profile.business,
      profileLoading: state => state.user.status === "loading",
      profileLoaded: state => state.user.status === "success"
    })
  }
};
</script>
