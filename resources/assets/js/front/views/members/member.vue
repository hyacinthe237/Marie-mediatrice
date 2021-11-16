<template lang="html">
    <section class="_page">
        <Navbar :title="'Member Contributions'" backButton >
            <span v-if="canManageMembers">
                <i class="ion-edit mr-10" @click="openModal('editMember')"></i>
            </span>

            <span v-if="canManageEntries">
                <i class="ion-plus" @click="openModal('addEntry')"></i>
            </span>
        </Navbar>

        <div class="">
            <Spinner v-show="isLoading"></Spinner>

            <div class="pd-10">
                <h5 class="bold fs-20"><i class="ion-android-person"></i> {{ member.name }}</h5>
                <label class="teal">{{ contributions | currency }}</label>
            </div>

            <div class="text-center">
                <vue-c3 :handler="handler"></vue-c3>
            </div>


            <div class="links bg-white mt-10">
                <Entry v-for="e in entries" :entry="e" :key="e.id" @click.native="editEntry(e)"></Entry>
            </div>
        </div>

        <EditModal @updated="updateMember" :member="member" :roles="roles"></EditModal>
        <AddEntryModal @added="entryEvent" :funds="funds" :member="member"></AddEntryModal>
        <EditEntryModal @updated="entryEvent" :entry="editingEntry" :funds="funds"></EditEntryModal>

    </section>
</template>

<script>
import _ from 'lodash'
import VueC3 from 'vue-c3'
import Entry from '../../components/finances/entry'
import EditModal from './components/edit-member'
import AddEntryModal from './components/add-entry'
import EditEntryModal from './components/edit-entry'

export default {
    data: () => ({
        roles: [],
        funds: [],
        member: {},
        editingEntry: {},
        isLoading: false,
        handler: new Vue()
    }),

    components: { Entry, VueC3, EditModal, AddEntryModal, EditEntryModal },

    mounted () {
        this.getMember()
        this.getFunds()
        this.getMemberFunds()
        this.roles = _roles

        if (!this.organization) {
            this.$store.dispatch('organization/getOrganization')
        }
    },

    computed: {
        organization () {
            return this.$store.state.organization.organization
        },

        contributions () {
            if (this.member.entries) {
                return this.member.entries.reduce((a, b) => a + b.amount, 0)
            } return 0
        },

        entries () {
            return this.member.entries
        }
    },

    methods: {
        async getMember () {
            this.isLoading = true
            const id = this.$route.params.id

             try {
                 const response = await axios.get(`members/${ id }`)
                 this.member = response.data
             } catch (e) {
                 console.log(e)
             }
             this.isLoading = false
        },

        getFunds () {
             axios.get(`/funds`)
             .then(response => {
                 this.funds = response.data
             })
        },

        getMemberFunds () {
             axios.get(`members/${ this.$route.params.id }/funds`)
             .then(response => {
                 const funds = response.data
                 this.buildPie(funds)
             })
        },

        buildPie (funds) {
            let columns = []
            let colours = {}

            funds.forEach((fund, index) => {
                const total = fund.entries.reduce((a, b) => a + b.amount, 0)
                colours[fund.name] = _colours[index]
                columns.push([ fund.name, total ])
            })

            const options = {
                data: {
                    columns: columns,
                    colors: colours,
                    type: 'donut'
                },

                donut: {
                    title: 'Contributions'
                }
            }

            this.handler.$emit('init', options)
        },

        updateMember (val) {
            this.member = val
            this.$sweet.show({
                type: 'success',
                title: 'Member successfully updated'
            })
        },

        openModal (name) {
            window.$(`#${ name }`).modal('show')
        },


        editEntry (entry) {
            if (this.canManageEntries) {
                this.editingEntry = _.cloneDeep(entry)
                window.$('#editEntry').modal('show')
            }
        },

        entryEvent (val) {
            this.getMemberFunds()
            this.getMember()
            this.$sweet.show({ type: 'success', title: val })
        }
    }
}
</script>
