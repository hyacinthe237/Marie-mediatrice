<template lang="html">
    <div class="">
        <Modal :title="'New Event'" :slug="'addEvent'">
            <form class="_form" @submit.prevent>
                <div class="form-group">
                    <label>Event title</label>
                    <input type="text"
                        class="form-control input-lg input-white"
                        placeholder="Event title"
                        name="title"
                        required
                        v-model="ghost.title">
                </div>

                <div class="form-group">
                    <label>Date</label>
                    <datepicker format="DD/MM/YYYY"
                        :classDesign="'form-control input-lg input-white'"
                        :initial-date="today"
                        @changed="dateChanged"
                        :name="'made_on'">
                    </datepicker>
                </div>

                <div class="row">
                    <div class="col-xs-6">
                        <div class="form-group">
                            <label>Hours</label>
                            <select class="form-control input-lg input-white" v-model="ghost.hours">
                                <option v-for="hour in hours" :value="hour">{{ hour }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-xs-6">
                        <div class="form-group">
                            <label>Minutes</label>
                            <select class="form-control input-lg input-white" v-model="ghost.minutes">
                                <option v-for="minute in minutes" :value="minute">{{ minute }}</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Event host</label>
                    <select class="form-control input-lg input-white" v-model="ghost.host">
                        <option v-for="member in members" :value="member.id">{{ member.name }}</option>
                    </select>
                </div>


                <div class="form-group">
                    <label>Event address</label>
                    <input type="text"
                        class="form-control input-lg input-white"
                        placeholder="Event address"
                        name="address"
                        v-model="ghost.address">
                </div>

                <Spinner v-show="isLoading"></Spinner>

                <div class="mt-20">
                    <button type="submit"
                        :disabled="isLoading"
                        @click="checkBeforeSave()"
                        class="btn btn-lg btn-block btn-success elevated">
                        <span v-show="!isLoading">SAVE ENTRY</span>
                        <span v-show="isLoading">SAVING...</span>
                    </button>
                </div>
            </form>
        </Modal>
    </div>
</template>

<script>
import moment from 'moment'
import Datepicker from '../../../components/datepicker/Datepicker'
import Modal from '../../../components/modal/modal'

export default {
    data: () => ({
        ghost: {},
        hours: [],
        minutes: [],
        today: moment()
    }),

    props: ['members'],

    components: { Modal, datepicker: Datepicker },

    mounted () {
        this.makeTimes()
        this.resetGhost()
    },

    watch: {
        'ghost.host': function (id) {
            let member = this.members.filter(m => m.id == id)

            if (member.length) {
                this.ghost.address = member[0].address
            }
        }
    },


    methods: {
        dateChanged (date) {
            this.ghost.date = date.format('YYYY-MM-DD')
        },

        checkBeforeSave () {
            if (!this.ghost.title) {
                return this.$sweet.show({ type: 'error', title: 'Please enter an event title' })
            }

            this.save()
        },

        makeTimes () {
            for (let i = 0; i < 60; i+=5) {
                if (i < 10) {
                    let value = '0' + i
                    this.minutes.push(value)
                } else {
                    this.minutes.push(i)
                }
            }

            for (let i = 0; i < 24; i++) {
                if (i < 10) {
                    let value = '0' + i
                    this.hours.push(value)
                } else {
                    this.hours.push(i)
                }
            }
        },

        async save () {
            this.isLoading = true

            try {
                const response = await axios.post('events', this.ghost)
                this.$emit('added', 'New event successfully added')

                window.$('.modal').modal('hide')
                this.resetGhost()
            }
            catch (e) {
                this.$sweet.show({
                    type: 'error',
                    title: e.response.data
                })
            }

            this.isLoading = false
        },

        resetGhost () {
            this.ghost = {
                hours: '15',
                minutes: '00',
                host: '',
                date: this.today
            }
        }
    }
}
</script>
