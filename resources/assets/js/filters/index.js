
import Vue from 'vue'
import moment from 'moment'

Vue.filter('date', function(value, format) {
    const formatString = format || 'DD MMMM YYYY'
    return moment(value).format(formatString)
})

Vue.filter('number', function(value) {
    value = parseInt(value)
    return value.toFixed(0).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,")
})

Vue.filter('currency', function(value, code) {
    code = 'AUD'
    value = parseInt(value) / 100
    return value.toFixed(2).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,") + ' ' + code
})
