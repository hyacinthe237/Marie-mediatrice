
var Helpr = {}

Helpr.install = function (Vue, options) {
    Vue.prototype.$helpr = {
        buildLocations (els) {
            els.forEach(el => {
                el.description = el.suburb + ', ' + el.state + ', ' + el.postcode
            })
        },
    }
}
 export default Helpr
