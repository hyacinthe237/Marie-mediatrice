import * as types from '../mutation-types'

export default {
    namespaced: true,
    state: {
        user: {},
    },

    mutations: {
        [types.SET_USER] (state, user) {
            state.user = user
        }
    },

    actions: {
        getUser (context) {
            axios.get('account')
            .then(response => context.commit(types.SET_USER, response.data))
            .catch(error => console.log(error))
        }
    }
}
