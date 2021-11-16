<template lang="html">
    <div class="">
        <Modal :title="'New Contribution'" :slug="'addEntry'">
            <form class="_form" @submit.prevent>
                <div class="form-group">
                    <label>Select a fund</label>
                    <select class="form-control input-lg input-white" v-model="ghost.fund_id">
                        <option v-for="f in funds" :value="f.id">{{ f.name }}</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Date of payment</label>
                    <datepicker format="DD/MM/YYYY"
                        :classDesign="'form-control input-lg input-white'"
                        :disable-future-days="true"
                        :initial-date="today"
                        @changed="dateChanged"
                        :name="'made_on'">
                    </datepicker>
                </div>

                <div class="form-group">
                    <label>Amount</label>
                    <input type="text"
                        class="form-control input-lg input-white"
                        placeholder="Entry Amount"
                        v-model="ghost.amount">
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
        </Modal>
    </div>
</template>

<script>
import moment from 'moment'
import Datepicker from '../../../components/datepicker/Datepicker'
import Modal from '../../../components/modal/modal'

export default {
    props: ['funds', 'member'],

    data: () => ({
        ghost: {},
        origins: [],
        today: moment()
    }),

    components: { Modal, datepicker: Datepicker },

    mounted () {
        if (this.funds.length > 0)
        this.ghost.fund_id = this.funds[0].id
        this.ghost.member_id = this.member.id
        this.ghost.origin = 'transfer'

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
        }
    },

    methods: {
        dateChanged (date) {
            this.ghost.made_on = date.format('YYYY-MM-DD')
        },

        checkBeforeSave () {
            if (!this.ghost.amount) {
                return this.$sweet.show({ type: 'error', title: 'Please enter an amount' })
            }

            if (this.ghost.amount < 1) {
                return this.$sweet.show({ type: 'error', title: 'Please enter a positive amount' })
            }

            this.save()
        },

        async save () {
            this.isLoading = true

            try {
                const response = await axios.post('entries', this.ghost)
                this.$emit('added', 'New entry successfully added')

                this.ghost.amount = ''
                this.ghost.fund_id = this.funds[0].id

                window.$('.modal').modal('hide')
            }
            catch (e) {
                this.$sweet.show({
                    type: 'error',
                    title: e.response.data
                })
            }

            this.isLoading = false
        }
    }
}
</script>
