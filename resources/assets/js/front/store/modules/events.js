import * as types from '../mutation-types'
import moment from 'moment'

export default {
    namespaced: true,
    state: {
        event: {},
        all: [],
        selectedDay: moment().format('DD-MM-YYYY')
    },

    mutations: {
        [types.SET_EVENT] (state, event) {
            state.event = event
        },
        [types.SET_EVENTS] (state, events) {
            state.all = events
        },
        [types.SET_SELECTED_DAY] (state, date) {
            state.selectedDay = date
        }
    },

    actions: {
        getEvents (context) {
            axios.get('events')
            .then(response => {
                let events = response.data
                let upcoming = events.filter(e => {
                    return !moment(e.start_date).isBefore(moment(), 'day')
                })
                context.commit(types.SET_EVENTS, upcoming)
            })
        },

        add (context, ev) {
            axios.post('events', ev)
            .then(response => {
                context.dispatch('getEvents')
            })
        },

        del (context, ev) {
            axios.delete('events/' + ev.id)
            .then(() => context.dispatch('getEvents'))
            .catch(error => console.log(error))
        }
    }
}
