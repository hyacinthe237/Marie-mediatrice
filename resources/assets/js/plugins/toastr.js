import Vue from 'vue'
import toastr from 'toastr'

var Toastr = {}

Toastr.install = function (Vue, options) {
    Vue.prototype.$toastr = {
        success(title, msg) {
            return toastr.success(msg, title)
        },

        info (title, msg) {
            return toastr.info(msg, title)
        },

        error(title, msg) {
            return toastr.error(msg, title)
        }
    }
}
 export default Toastr
