/***
 * This is the initial js which is loaded and all other js is compiled into when public
 */
require("./bootstrap");
import { BootstrapVue, IconsPlugin } from "bootstrap-vue";
import PortalVue from "portal-vue";
import Vue from "vue";
import VueRouter from "vue-router";
import App from "./app/App";
import router from "./router.js";
import store from './store/';
window.Vue = Vue;
Vue.config.devtools = true;
Vue.use(VueRouter);
//PortalVue for bootstrap
Vue.use(PortalVue);
// Install BootstrapVue
Vue.use(BootstrapVue);
// Optionally install the BootstrapVue icon components plugin
Vue.use(IconsPlugin);

const token = {
    token: localStorage.getItem('token')
}
if (!!token.token) {
    axios.defaults.headers.common['Authorization'] = "Bearer " + token.token;
}

// store.dispatch('auth/attempt', token).then(() => {
new Vue({
    router,
    store,
    render: h => h(App)
}).$mount('#app');
// })