import Vue from 'vue'
import swal from 'sweetalert2'

var Sweet = {}

Sweet.install = function (Vue, options) {
    Vue.prototype.$sweet = {
        show(...args) {
            swal(...args)
        }
    }
}

export default Sweet
