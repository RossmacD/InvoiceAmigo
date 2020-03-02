import axios from "axios";
import router from "../../router";
import { AUTH_ERROR, AUTH_LOGOUT, AUTH_REQUEST, AUTH_SUCCESS, SOCIAL_AUTH_REQUEST } from "../actions/auth";
import { USER_REQUEST } from "../actions/user";

const state = {
    token: localStorage.getItem("token") || "",
    status: "",
    hasLoadedOnce: false
};
const getters = {
    isAuthenticated: state => !!state.token,
    authStatus: state => state.status
};
const actions = {
    [AUTH_REQUEST]: ({ commit, dispatch }, user) => {
        return new Promise((resolve, reject) => {
            // The Promise used for router redirect in login
            commit(AUTH_REQUEST);
            axios({ url: "api/login", data: user, method: "POST" })
                .then(resp => {
                    const token = resp.data.token;
                    localStorage.setItem("token", token); // store the token in localstorage
                    axios.defaults.headers.common["Authorization"] =
                        "Bearer " + token;
                    commit(AUTH_SUCCESS, token);
                    // you have your token, now log in your user :)
                    dispatch(USER_REQUEST);
                    resolve(resp);
                })
                .catch(err => {
                    commit(AUTH_ERROR, err);
                    localStorage.removeItem("token"); // if the request fails, remove any possible user token if possible
                    reject(err.response); // Send back error response
                });
        });
    },
    [SOCIAL_AUTH_REQUEST]: ({ commit, dispatch }, socaialdetails) => {
        return new Promise((resolve, reject) => {
            commit(SOCIAL_AUTH_REQUEST);
            axios({
                url: "api/callback",
                params: socaialdetails,
                method: "POST"
            })
                .then(resp => {
                    const token = resp.data.token;
                    localStorage.setItem("token", token); // store the token in localstorage
                    axios.defaults.headers.common["Authorization"] =
                        "Bearer " + token;
                    commit(AUTH_SUCCESS, token);
                    // you have your token, now log in your user :)
                    dispatch(USER_REQUEST);
                    resolve(resp);
                })
                .catch(err => {
                    commit(AUTH_ERROR, err);
                    localStorage.removeItem("token"); // if the request fails, remove any possible user token if possible
                    reject(err.response); // Send back error response
                });
        });
    },
    [AUTH_LOGOUT]: ({ commit, dispatch }) => {
        return new Promise((resolve, reject) => {
            axios({ url: "api/logout", method: "GET" })
                .then(resp => {
                    commit(AUTH_LOGOUT);
                    localStorage.removeItem("token"); // clear your user's token from localstorage
                    router.push("/");
                    resolve(resp);
                })
                .catch(err => {
                    commit(AUTH_ERROR, err);
                    localStorage.removeItem("token"); // if the request fails, remove any possible user token if possible
                    
                    reject(err);
                });
            pusher.disconnect()
            resolve(resp);
        });
    }
};
const mutations = {
    [AUTH_REQUEST]: state => {
        state.status = "loading";
    },
    [AUTH_SUCCESS]: (state, resp) => {
        state.status = "success";
        state.token = resp;
        state.hasLoadedOnce = true;
    },
    [SOCIAL_AUTH_REQUEST]: (state, resp) => {
        state.status = "loading";
    },
    [AUTH_ERROR]: state => {
        state.status = "error";
        state.hasLoadedOnce = true;
    },
    [AUTH_LOGOUT]: state => {
        state.token = "";
    }
};

export default {
    state,
    getters,
    actions,
    mutations
};
