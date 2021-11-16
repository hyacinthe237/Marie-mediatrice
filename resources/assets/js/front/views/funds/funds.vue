<template lang="html">
    <section class="_page">
        <Navbar :title="'Association Funds'" backButton>
            <span v-if="canManageFunds">
                <i class="ion-plus"></i>
            </span>
        </Navbar>

        <div class="container">
            <Spinner v-show="isLoading"></Spinner>


            <div class="mt-10" v-show="!isLoading">
                <div class="row">
                    <div class="col-xs-6">
                        <label class="teal bold fs-15">Entries</label>
                        <h5 class="bold margin-0 text-primary fs-15">{{ entriesTotal | currency }}</h5>
                    </div>

                    <div class="col-xs-6 text-right">
                        <label class="teal bold fs-15">Expenses</label>
                        <h5 class="bold margin-0 text-danger fs-15">{{ expenseTotal | currency }}</h5>
                    </div>
                </div>

                <div class="text-center" style="padding-top:-20px">
                    <label class="teal bold">Balance</label>
                    <h5 class="bold margin-0">{{ balance | currency }}</h5>
                </div>
            </div>

            <div class="text-center mt-20">
                <vue-c3 :handler="handler"></vue-c3>
            </div>

        </div>

        <div class="links bg-white mt-20">
            <Fund v-for="f in funds" :key="f.id" :fund="f"></Fund>
        </div>

    </section>
</template>

<script>
import VueC3 from 'vue-c3'
import Fund from './components/fund'

export default {
    name: 'home',
    data () {
        return {
            funds: [],
            init: false,
            entriesTotal: 0,
            expenseTotal: 0,
            isLoading: false,
            handler: new Vue()
        }
    },

    components: { VueC3, Fund },

    mounted () {
        this.getExpenses()
        this.getFunds()
    },

    computed: {
        balance () {
            return this.entriesTotal - this.expenseTotal
        }
    },

    methods: {
        getExpenses () {
            axios.get('expenses')
            .then(response => {
                response.data.forEach(e => this.expenseTotal += e.amount )
            })
        },

        async getFunds () {
            this.isLoading = true
             try {
                 const response = await axios.get('funds?entries=1')
                 this.funds = response.data
                 this.sumup()
                 this.buildPie()
             } catch (e) {
                 console.log(e)
             }
             this.isLoading = false
        },

        sumup () {
            this.funds.forEach (fund => {
                fund.entries.forEach (entry => {
                    this.entriesTotal += entry.amount
                })
            })
        },

        buildPie () {
            let columns = []
            let colours = {}

            this.funds.forEach((fund, index) => {
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
                    title: 'Entries Breakdown'
                }
            }

            this.handler.$emit('init', options)
        }
    }
}
</script>
