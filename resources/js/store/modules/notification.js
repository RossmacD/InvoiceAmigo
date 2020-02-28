import { ADD_NOTIFICATIONS, GET_NOTIFICATIONS } from "../actions/notification";

let state = {
    notifications: []
};

let getters = {
    notifications: state => {
        return state.notifications
    }
}

let mutations = {
    [GET_NOTIFICATIONS]: (state, comments) => {
        state.comments = comments
    },
    [ADD_NOTIFICATIONS]: (state, notification) => {
        state.notifications = [...state.notifications, notification]
    }
}

let actions = {
    [ADD_NOTIFICATIONS]: ({ commit }, notification) => {
        commit(ADD_NOTIFICATIONS, notification);
        // return new Promise((resolve, reject) => {
        //     axios.post(`/comments`, comment)
        //         .then(response => {
        //             resolve(response)
        //         }).catch(err => {
        //             reject(err)
        //         })
        // })
    },

    [GET_NOTIFICATIONS]: ({ commit }) => {
        //     axios.get('/comments')
        //         .then(res => {
        //             {
        //                 commit('GET_COMMENTS', res.data)
        //             }
        //         })
        //         .catch(err => {
        //             console.log(err)
        //         })
    }
}



export default {
    state,
    getters,
    mutations,
    actions
}