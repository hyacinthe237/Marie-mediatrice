<template lang="html">
    <section class="_page">
        <Navbar :title="'Events'" backButton >
            <span v-if="canManageEvents">
                <i class="ion-plus" @click="openAddEventModal()"></i>
            </span>

            <div class="clearfix"></div>
        </Navbar>


        <div class="tabs">
            <div class="tab" :class="[{ active: tab == 1 }]" @click="showTab(1)">
                Upcoming Events
            </div>
            <div class="tab" :class="[{ active: tab == 2 }]" @click="showTab(2)">
                Past Events
            </div>
        </div>


        <div class="">
            <Spinner v-show="isLoading"></Spinner>

            <div class="text-center teal mt-20 " v-show="!isLoading && !hasEvents">
                <i class="fs-25 ion-ios-information-outline mr-10"></i>

                <div class="fs-18">
                    Nothing to display
                </div>
            </div>


            <div class="mt-0" v-show="hasEvents">
                <div class="events">
                    <div class="tab-content" v-show="tab == 1">
                        <Event v-for="e in upcoming" :key="e.id" :event="e"></Event>
                    </div>

                    <div class="tab-content" v-show="tab == 2">
                        <Event v-for="e in pastEvents" :key="e.id" :event="e"></Event>
                    </div>
                </div>
            </div>
        </div>

        <AddModal @added="addEvent" :members="members"></AddModal>
    </section>
</template>

<script>
import moment from 'moment'
import Event from './components/event'
import AddModal from './components/add-event'

export default {
    name: 'events',
    data () {
        return {
            isLoading: false,
            events: [],
            members: [],
            tab: 1,
        }
    },

    components: { Event, AddModal },

    mounted () {
        this.getMembers()
        this.getEvents()
    },

    computed: {
        hasEvents () {
            return this.events.length
        },

        pastEvents () {
            return this.events.filter(event => moment(event.date).endOf('day').isBefore(moment()))
            .sort((a, b) => moment(a.date).isBefore(moment(b.date)))
        },

        upcoming () {
            return this.events.filter(event => moment(event.date).endOf('day').isAfter(moment()))
        },
    },

    methods: {
        async getEvents () {
            this.isLoading = true
             try {
                 const response = await axios.get('events')
                 this.events = response.data
             } catch (e) {
                 console.log(e)
             }
             this.isLoading = false
        },

        getMembers () {
            axios.get('members')
            .then(response => {
                this.members = response.data
                this.members.unshift({ id: '', name: 'Select host'})
            })
            .catch(error => console.log(error))
        },

        goto (name) {
            this.$router.push({ name })
        },

        showTab (id) {
            this.tab = id
        },

        openAddEventModal () {
            console.log('opening openAddEventModal')
            window.$('#addEvent').modal('show')
        },

        addEvent () {
            this.getEvents()
            this.$sweet.show({
                type: 'success',
                title: 'New event successfully added'
            })
        }
    }
}
</script>
