<template lang="html">
    <section class="_page">
        <Navbar :title="'Home'"></Navbar>

        <div class="container">
            <Spinner v-show="isLoading"></Spinner>

            <h5 class="bold mt-10">{{ organization.name }}</h5>

            <ul class="list-unstyled">
                <li v-show="location">{{ location }}</li>
            </ul>

            <h5 class=" bold mt-20">Bank Account</h5>
            <ul class="list-unstyled">
                <li><strong>Name:</strong> CAA Sydney Chapter</li>
                <li><strong>BSB:</strong> 012-332</li>
                <li><strong>Number:</strong>4609-93306</li>
            </ul>

        </div>

        <div class="links bg-white mt-20">
            <div class="link border-top border-bottom bold" @click="goto('events')">
                <span class="pull-right"><i class="ion-chevron-right"></i></span>
                <i class="ion-calendar mr-10"></i> Events
            </div>

            <div class="link border-bottom bold" @click="goto('funds')">
                <span class="pull-right"><i class="ion-chevron-right"></i></span>
                <i class="ion-cash mr-10"></i> Funds
            </div>

            <div class="link border-bottom bold" @click="goto('expenses')">
                <span class="pull-right"><i class="ion-chevron-right"></i></span>
                <i class="ion-arrow-return-left mr-10"></i> Expenses
            </div>

            <div class="link border-bottom bold" @click="goto('members')">
                <span class="pull-right"><i class="ion-chevron-right"></i></span>
                <i class="ion-android-people mr-10"></i> Members
            </div>

            <div class="link border-bottom bold" @click="goto('about')">
                <span class="pull-right"><i class="ion-chevron-right" ></i></span>
                <i class="ion-information-circled mr-10"></i> About
            </div>
        </div>

    </section>
</template>

<script>
import _ from 'lodash'

export default {
    name: 'home',
    data () {
        return {
            isLoading: false
        }
    },

    mounted () {
        this.getOrganization()
    },

    computed: {
        organization () {
            return this.$store.state.organization.organization
        },

        location () {
            let str = ''
            if (this.organization.city) {
                str = this.organization.city
                if (this.organization.country) {
                    str += ', ' + this.organization.country.name
                }
            } else {
                if (this.organization.country) {
                    str = this.organization.country.name
                }
            }
            return str
        }
    },

    methods: {
        async getOrganization () {
            this.isLoading = true
             try {
                 const response = await axios.get('organization')
                 this.$store.commit('organization/SET_ORG', response.data)
             } catch (e) {
                 console.log(e)
             }
             this.isLoading = false
        },

        goto (name) {
            this.$router.push({ name })
        }
    }
}
</script>
