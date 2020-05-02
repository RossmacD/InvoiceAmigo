<template>
    <div class='drawer shadow-lg ' ref="drawer"  @focusout="closeDrawer"
        tabindex="0">
      <b-container>
        <b-row class='py-3 px-2'>
          <b-col>
            <h1 >Notifications:</h1>
          </b-col>
          <b-col @click='closeDrawer' class='text-right pointer'>
            <!-- style='width: 1rem; height: 1rem' -->
            <h1>
              <b-icon variant='dark' icon='x' ></b-icon>
            </h1>
          </b-col>
        </b-row>
        <b-nav vertical v-for='notification in notifications' :key='notification.id' class="mt-2">
          <!-- <b-nav-item :disabled="!!!notification.link">{{notification.message}}</b-nav-item> -->
          <b-nav-item :disabled="!!!notification.link" :to="notification.link">{{notification.message}}</b-nav-item>
        </b-nav>
      </b-container>
    </div>
</template>

<script>
import axios from "axios";
import Vue from "vue";
import { mapGetters, mapState } from "vuex";
import { FormPlugin, ButtonPlugin, BIcon } from "bootstrap-vue";


export default {
  name: "Notifications",
  mounted(){
    // console.log(this.$refs.drawer)
    this.$refs.drawer.focus()
  },
   components: {
    BIcon
  },
  props:{scrollPos:{type:Number,default:0}},
  data() {
    return {
      
    };
  },
  methods:{
    closeDrawer(){
      this.$emit("closeDrawer");
    },
    
  },
  computed: {
    ...mapGetters(["notifications", "getProfile"]),
    ...mapState({
      name: state => `${state.user.profile.name}`,
      id: state => `${state.user.profile.id}`
    })
  }
};
</script>
