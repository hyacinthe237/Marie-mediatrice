window.Vue = require('vue')
window.eventBus = new Vue()

import store from './front/store/store'
import router from './front/router'
import sweet from './plugins/sweet'
import toastr from './plugins/toastr'

require('./bootstrap')
require('./filters')
require('./mixins')
require('./ui')

Vue.use(sweet)
Vue.use(toastr)

Vue.component('core', require('./front/core'))
Vue.component('auth-login', require('./front/views/auth/login'))
Vue.component('Navbar', require('./front/components/nav/nav'))
Vue.component('Spinner', require('./front/components/loaders/spinner'))

Vue.config.ignoredElements = [/^ion-/]

const app = new Vue({
    el: '#app',
    store,
    router
})
