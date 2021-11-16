<template lang="html">
    <div class="">
        <Modal :title="'Edit Contribution'" :slug="'editEntry'">
            <form class="_form" @submit.prevent>
                <div class="form-group">
                    <label>Change fund</label>
                    <select class="form-control input-lg input-white" v-model="ghost.fund_id">
                        <option v-for="f in funds" :value="f.id">{{ f.name }}</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Date of payment</label>
                    <datepicker format="DD/MM/YYYY"
                        :classDesign="'form-control input-lg input-white'"
                        @changed="dateChanged"
                        :disable-future-days="true"
                        :initial-date="today"
                        :name="'made_on'">
                    </datepicker>
                </div>

                <div class="form-group">
                    <label>Amount</label>
                    <input type="number"
                        class="form-control input-lg input-white"
                        placeholder="Entry Amount"
                        v-model="amount">
                </div>

                <div class="form-group">
                    <label>Origin</label>
                    <select class="form-control input-lg input-white" v-model="ghost.origin">
                        <option v-for="f in origins" :value="f.id">{{ f.label }}</option>
                    </select>
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


            <div class="mt-40 text-center">
                <z-btn :colour="'white'" :size="'md'" round @click.native="showDelete = !showDelete">
                    <i class="ion-trash-b"></i> Delete Entry
                </z-btn>


                <div class="mt-20" v-show="showDelete">
                    <z-btn :colour="'danger'" :disabled="isDeleting" elevated @click.native="deleteEntry()">
                        <span v-show="!isDeleting">Confirm Delete</span>
                        <span v-show="isDeleting">Deleting...</span>
                    </z-btn>
                </div>
            </div>
        </Modal>
    </div>
</template>

<script>
import _ from 'lodash'
import moment from 'moment'
import Datepicker from '../../../components/datepicker/Datepicker'
import Modal from '../../../components/modal/modal'

export default {
    props: ['funds', 'member', 'entry'],

    data: () => ({
        ghost: {},
        origins: [],
        amount: 0,
        showDelete: false,
        isDeleting: false,
        today: moment()
    }),

    components: { Modal, datepicker: Datepicker },

    mounted () {
        this.ghost.origin = 'transfer'
        this.ghost.made_on = moment().format('YYYY-MM-DD')

        this.origins = [
            { id: 'transfer', label: 'Bank Transfer'},
            { id: 'cash', label: 'Cash In Hand'}
        ]
    },

    watch: {
        funds (val) {
            if (val.length > 0)
            this.ghost.fund_id = val[0].id
        },

        member (val) {
            this.ghost.member_id = val.id
        },

        entry (value) {
            this.ghost = _.cloneDeep(value)
            this.today = moment(value.made_on)
            this.amount = (value.amount / 100).toFixed(2)
        }
    },

    methods: {
        dateChanged (date) {
            this.ghost.made_on = date.format('YYYY-MM-DD')
        },

        checkBeforeSave () {
            if (this.amount < 1) {
                return this.$sweet.show({ type: 'error', title: 'Please enter a positive amount' })
            }

            this.save()
        },

        async save () {
            this.isLoading = true
            let payload = _.cloneDeep(this.ghost)
            payload.amount = this.amount

            try {
                const response = await axios.put('entries', payload)
                this.$emit('updated', 'Contribution has been edited')
                window.$('.modal').modal('hide')
            }
            catch (e) {
                this.$sweet.show({
                    type: 'error',
                    title: e.response.data
                })
            }

            this.isLoading = false
        },

        async deleteEntry () {
            this.isDeleting = true

            try {
                const response = await axios.delete('entries/' + this.ghost.id)
                this.$emit('updated', 'Contribution successfully deleted')
                window.$('.modal').modal('hide')
            } catch (error) {
                this.$sweet.show({ type: 'error', title: error.response.data })
            }

            this.isDeleting = false
        }
    }
}
</script>
