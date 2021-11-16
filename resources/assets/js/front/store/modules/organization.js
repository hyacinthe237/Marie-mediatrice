import * as types from '../mutation-types'

export default {
    namespaced: true,
    state: {
        organization: {},
    },

    mutations: {
        [types.SET_ORG] (state, org) {
            state.organization = org
        }
    },

    actions: {
        getOrganization (context) {
            axios.get('organization')
            .then(response => context.commit(types.SET_ORG, response.data))
            .catch(error => console.log(error))
        }
    }
}
