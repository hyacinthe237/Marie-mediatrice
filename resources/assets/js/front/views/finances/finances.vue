<template lang="html">
    <section class="_page">
        <Navbar :title="'My Finances'"></Navbar>

        <div class="">
            <Spinner v-show="isLoading"></Spinner>

            <div class="pl-10">
                <h5 class="bold teal fs-15">Total Contributions</h5>
                <h5 class="bold fs-20">{{ contributions | currency }}</h5>
            </div>

            <div class="text-center">
                <vue-c3 :handler="handler"></vue-c3>
            </div>

            <div class="links bg-white mt-20">
                <Entry v-for="e in entries" :entry="e" :key="e.id"></Entry>
            </div>
        </div>

    </section>
</template>

<script>
import VueC3 from 'vue-c3'
import Entry from '../../components/finances/entry'

export default {
    data: () => ({
        member: {},
        isLoading: false,
        handler: new Vue()
    }),

    components: { Entry, VueC3 },

    mounted () {
        this.getMember()
        this.getFunds()
    },

    computed: {
        contributions () {
            if (this.member.entries) {
                return this.member.entries.reduce((a, b) => a + b.amount, 0)
            } return 0
        },

        entries () {
            return this.member.entries
        },
    },

    methods: {
        async getMember () {
            this.isLoading = true

             try {
                 const response = await axios.get(`account`)
                 this.member = response.data
             } catch (e) {
                 console.log(e)
             }
             this.isLoading = false
        },

        async getFunds () {
             try {
                 const response = await axios.get(`account/funds`)
                 this.buildPie(response.data)
             } catch (e) {
                 console.log(e)
             }
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
        }
    }
}
</script>
