require('./bootstrap');
// import Vue from 'vue';
// require('vue')
import Vue from 'vue';
import App from './components/App'
import VueRouter from 'vue-router'
import router from './router.js';
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue'
import PortalVue from 'portal-vue'

window.Vue = Vue;
Vue.config.devtools = true;
Vue.use(VueRouter)

Vue.use(PortalVue)

// Install BootstrapVue
Vue.use(BootstrapVue)
// Optionally install the BootstrapVue icon components plugin
Vue.use(IconsPlugin)

new Vue({
    router,
    render: h => h(App)
}).$mount('#app');