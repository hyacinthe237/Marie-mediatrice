<template lang="html">
    <section class="_page">
        <Navbar :title="`Members (${ members.length })`" backButton >
            <i class="ion-android-funnel" @click="openFilterModal()"></i>
            <i class="ion-search" @click="showSearch = true"></i>

            <span v-if="canManageMembers">
                <i class="ion-plus" @click="openAddMemberModal()"></i>
            </span>

            <!-- Search container  -->
            <div class="search-container" v-show="showSearch">
                <div class="row">
                    <div class="col-xs-10">
                        <div class="_form">
                            <input type="text" v-model="keyword" class="form-control input-lg">
                        </div>
                    </div>

                    <div class="col-xs-2">
                        <div class="_close">
                            <i class="ion-close" @click="showSearch = false"></i>
                        </div>
                    </div>
                </div>
            </div>
        </Navbar>


        <!-- Content  -->
        <div class="">
            <Spinner v-show="isLoading"></Spinner>

            <div class="text-center teal mt-20 " v-show="!isLoading && !hasMembers">
                <i class="fs-25 ion-ios-information-outline mr-10"></i>

                <div class="fs-18">
                    Nothing to display
                </div>
            </div>


            <div class="mt-0" v-show="hasMembers">
                <div class="links bg-white">
                    <Member v-for="m in filtered" :key="m.id" :member="m"></Member>
                </div>
            </div>
        </div>

        <AddModal @added="addMember"></AddModal>
        <FilterMembers @filtered="filterMembers"></FilterMembers>
    </section>
</template>

<script>
import _ from 'lodash'
import Member from './components/member'
import AddModal from './components/add-member'
import FilterMembers from './components/filter'

export default {
    data: () => ({
        filters: {},
        members: [],
        keyword: '',
        isLoading: false,
        showSearch: false
    }),

    components: { Member, AddModal, FilterMembers },

    mounted () {
        this.getMembers()
        this.filters = { by: 'name', order: 'ascending' }

        if (!this.organization) {
            this.$store.dispatch('organization/getOrganization')
        }
    },

    computed: {
        hasMembers () {
            return this.members.length
        },

        filtered () {
            let filteredMembers = _.orderBy(this.members, this.filters.by)

            if (this.filters.order === 'descending') {
                filteredMembers = _.reverse(filteredMembers)
            }

            if (this.keyword.length > 0) {
                filteredMembers = filteredMembers.filter(m => m.name.toLowerCase().includes(this.keyword.toLowerCase()))
            }
            
            return filteredMembers
        }
    },

    methods: {
        async getMembers () {
            this.isLoading = true
             try {
                 const response = await axios.get('members')
                 this.members = response.data
                 this.members.forEach(member => {
                     member.total = 0
                     if (member.entries.length > 0) {
                         member.entries.forEach(entry => {
                             member.total += entry.amount / 100
                         })
                     }
                 })
             } catch (e) {
                 console.log(e)
             }
             this.isLoading = false
        },

        addMember (member) {
            this.members.unshift(member)
            this.$sweet.show({
                type: 'success',
                title: 'New member successfully added'
            })
        },

        filterMembers (filters) {
            console.log('new filters', filters)
            this.filters = filters
        },

        openAddMemberModal () {
            window.$('#addMember').modal('show')
        },

        openFilterModal () {
            window.$('#filterMembers').modal('show')
        }
    }
}
</script>
