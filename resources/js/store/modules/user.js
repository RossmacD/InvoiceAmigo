import axios from 'axios';
import Vue from "vue";
import { AUTH_LOGOUT } from "../actions/auth";
import { USER_ERROR, USER_REQUEST, USER_SUCCESS } from "../actions/user";

const state = { status: "", profile: {} };

const getters = {
    getProfile: state => state.profile,
    isProfileLoaded: state => !!state.profile.name
};

const actions = {
    [USER_REQUEST]: ({ commit, dispatch }) => {
        commit(USER_REQUEST);
        axios({ url: 'api/user', method: 'GET' })
            .then(resp => {
                commit(USER_SUCCESS, resp);
            })
            .catch(err => {
                commit(USER_ERROR);
                dispatch(AUTH_LOGOUT);
            })
    }
};

const mutations = {
    [USER_REQUEST]: state => {
        state.status = "loading";
    },
    [USER_SUCCESS]: (state, resp) => {
        console.log("User SUCC")
        console.log(resp);
        state.status = "success";
        Vue.set(state, "profile", resp.data.user);
    },
    [USER_ERROR]: state => {
        state.status = "error";
    },
    [AUTH_LOGOUT]: state => {
        state.profile = {};
    }
};

export default {
    state,
    getters,
    actions,
    mutations
};