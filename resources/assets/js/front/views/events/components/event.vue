<template lang="html">
    <div class="event">
        <div class="date">{{ formattedDate }}</div>
        <div class="title">{{ event.title }}</div>
        <div class="meta" v-show="event.address">
            <i class="ion-location"></i> {{ event.address }}
        </div>
        <div class="meta" v-show="event.host">
            <i class="ion-android-person"></i> {{ event.host.name }}
        </div>
    </div>
</template>

<script>
import moment from 'moment'

export default {
    props: ['event'],

    computed: {
        formattedDate () {
            let d = moment(this.event.date).format('ddd, DD MMMM YYYY')
            if (this.event.hours) {
                d += ' - ' + this.event.hours

                if (this.event.minutes) {
                    d += ':' + this.event.minutes
                } else {
                    d += ':00'
                }
            }
            return d
        }
    }
}
</script>
