<template>
  <div>
    <h2 class='mb-4'>
      Your <span style="text-transform: capitalize;">{{itemName}}</span>
      <b-button :to='pageRoute+"create"' class='float-right'>+ New</b-button>
    </h2>
    <ErrorPage v-if="hitError"></ErrorPage>
    <LoadingPage v-else-if='!loaded'></LoadingPage>
    <EmptyIndex :indexType='itemName.substr(0,itemName.length-1)' v-else-if='items.length==0'></EmptyIndex>
    <b-card v-else v-for='(item,index) in items' v-bind:key='item.id' class="my-2" footer-bg-variant="light" :footer="item.created_at">
        <b-row>
          <b-col>
            <h5>{{ item.name }}</h5>
            <p>{{ item.description }}</p>
          </b-col>
          <b-col>
            <b-button class='float-right m-1' variant='secondary' :pressed='false' :to="pageRoute+item.id" size='sm'>
              <b-icon variant='light' icon='pen' style='width: 20px; height: 20px'></b-icon>
            </b-button>
            <DeleteButton class='float-right m-1' v-on:on-confirm='deleteProduct' :id='item.id' :index='index'></DeleteButton>
          </b-col>
        </b-row>
        <b-row>
          <b-col></b-col>
          <b-col>
            <h4 v-if="item.cost" class='float-right'>€{{ item.cost }} EUR</h4>
            <h4 v-else>{{ item.rate }} per {{ item.rate_unit }}</h4>
          </b-col>
        </b-row>
        </b-card>
        
  </div>
</template>


<script>
import axios from "axios";
import Vue from "vue";
import EmptyIndex from "./EmptyIndex";
import DeleteButton from "./DeleteButton";
import LoadingPage from "./LoadingPage"
import ErrorPage from "./ErrorPage"
import { SpinnerPlugin, ButtonPlugin, LayoutPlugin, CardPlugin } from "bootstrap-vue";
import { mapGetters, mapState } from "vuex";
Vue.use(SpinnerPlugin);
Vue.use(ButtonPlugin);
Vue.use(LayoutPlugin);
Vue.use(CardPlugin);
export default {
  name: "IndexBase",
  components: {
    EmptyIndex,
    DeleteButton,
    LoadingPage,
    ErrorPage
  },
  props:{ 
    items: {
      type: Array,
      required:true
    },
      itemName: {
      type: String,
      required: true
    },
    loaded:{
      type:Boolean,
      required:true
    },
    hitError:{
      type:Boolean,
      default:false
    }
  },
  data() {
    return {
      deleteText: "",
      apiRoute: "/api/"+this.itemName+"/",
      pageRoute:"/"+this.itemName+"/"
    };
  },
  methods: {
    deleteProduct(id, index) {
      const app = this;
      axios
        .delete(app.apiRoute + id)
        .then(function(response) {
          console.log(response);
          app.$delete(app.products, index);
        })
        .catch(function(error) {
          console.log(error.response);
        });
    },
    
  },
  computed:{
    ...mapGetters(["isAuthenticated"]),
    ...mapState({})
  }
};
</script>

