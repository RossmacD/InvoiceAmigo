<template>
  <div>
    <div class='myMain min-vh-95'>
      <Navbar  v-on:drawerOpen='handleDrawer'></Navbar>
      <b-overlay :show='drawerOpen' variant='dark' class='myMain flex1'>
        <main role='main' :class='mainClass'>
          <!-- Content -->
          <transition :name='transitionName' mode='out-in'>
            <router-view class='flex1'></router-view>
          </transition>
        </main>
        <template v-slot:overlay> <div></div>
        </template>
      </b-overlay>
    </div>
    <br/>
    <Footer></Footer>
  </div>
</template>

<script>
import Vue from "vue";
import Navbar from "../components/Navbar.vue";
import Footer from "../components/Footer.vue";
import { USER_REQUEST } from "../store/actions/user";
Pusher.logToConsole = true;
import { mapGetters, mapState } from "vuex";


export default {
  name: "App",
  components: {
    Navbar,
    Footer
  },
  data() {
    return {
      transitionName: "slide-right",
      mainClass: "myMain flex1",
      drawerOpen:false
    };
  },
  created: function() {
    if (!!localStorage.getItem("token")) {
      this.$store.dispatch(USER_REQUEST);
    }
    if (!this.$route.meta.mainClass) {
      this.mainClass="container mt-4 myMain flex1";
    }
    //Logout on Unauthourised
    // axios.interceptors.response.use(undefined, function(err) {
    //   return new Promise(function(resolve, reject) {
    //     if (err.status === 401 && err.config && !err.config.__isRetryRequest) {
    //       // if you ever get an unauthorized, logout the user
    //       this.$store.dispatch(AUTH_LOGOUT);
    //       // you can also redirect to /login if needed !
    //     }
    //     throw err;
    //   });
    // });
  },
  methods:{
    handleScroll(){
      console.log('ooasdoo')
      this.scrollPos = window.scrollY;
    },
    handleDrawer(drawerState){
      this.drawerOpen=drawerState;
    }
  },
  computed: {
    ...mapGetters(["getProfile","isAuthenticated"]),
    ...mapState({
    })
  },
  watch: {
    $route(to, from) {
      this.mainClass= to.meta.mainClass? "myMain flex1" :"container mt-4 myMain flex1";
     
     //Choose transition based on page location or amount of partitions in url
      if (!!to.meta.depthIndex&&!!from.meta.depthIndex) {
        if(to.meta.depthIndex<from.meta.depthIndex){
          this.transitionName = "slide-right";
        }else{
          this.transitionName = "slide-left";
        }
      } else {
        const toDepth = to.path.split("/").length;
        const fromDepth = from.path.split("/").length;
        if ((this.transitionName = toDepth < fromDepth)) {
          this.transitionName = "slide-right";
        } else {
          this.transitionName = "slide-left";
        }
      }
    }
  }
};
</script>

<style>
.slide-left-enter-active,
.slide-left-leave-active,
.slide-right-enter-active,
.slide-right-leave-active {
  transition-duration: 0.2s;
  transition-property: height, opacity, transform;
  transition-timing-function: cubic-bezier(0.55, 0, 0.1, 1);
  overflow: hidden;
}

.slide-left-enter,
.slide-right-leave-active {
  opacity: 0;
  transform: translate(2em, 0);
}

.slide-left-leave-active,
.slide-right-enter {
  opacity: 0;
  transform: translate(-2em, 0);
}
</style>
