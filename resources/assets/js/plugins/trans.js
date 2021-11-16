
export default function install (Vue) {
    Vue.prototype.trans = function (arg) {
        if (_lang)
        return _lang[arg]
    },

    Vue.prototype.$trans = function (arg) {
        if (_lang)
        return _lang[arg]
    }
}
