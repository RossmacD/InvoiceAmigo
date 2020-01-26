require('./bootstrap');
// import Vue from 'vue';
// require('vue')
// import './newBootVars'
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue';
import PortalVue from 'portal-vue';
import Vue from 'vue';
import VueRouter from 'vue-router';
import App from './app/App';
import router from './router.js';

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