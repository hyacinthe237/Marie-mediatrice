import Vue from 'vue'
import Vuex from 'vuex'
import UserModule from './modules/user'
import OrgModule from './modules/organization'

Vue.use(Vuex)

export default new Vuex.Store({
    strict: true,
    modules: {
        users: UserModule,
        organization: OrgModule
    }
})
