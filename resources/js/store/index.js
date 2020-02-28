import Vue from 'vue';
import Vuex from 'vuex';
import auth from './modules/auth';
import notification from './modules/notification';
import user from './modules/user';
Vue.use(Vuex);

export default new Vuex.Store({
    modules: {
        user,
        auth,
        notification
    }
});