<template lang="html">
    <section class="_page">
        <Navbar :title="'Association Expenses'" backButton>
            <span v-if="canManageExpenses">
                <i class="ion-plus"></i>
            </span>
        </Navbar>

        <div class="container">
            <Spinner v-show="isLoading"></Spinner>
            <label class="teal bold">Total Expenses</label>
            <h5 class="bold margin-0">{{ expenseTotal | currency }}</h5>
        </div>

        <div class="links bg-white mt-20">
            <div class="link border-bottom" v-for="e in expenses">
                <span class="pull-right bold">{{ e.amount | currency }}</i></span>
                {{ e.made_on | date }}

                <div class="teal fs-15">
                    {{ e.description }}
                </div>
            </div>
        </div>

    </section>
</template>

<script>
export default {
    data () {
        return {
            expenses: [],
            isLoading: false
        }
    },

    mounted () {
        this.getExpenses()
    },

    computed: {
        expenseTotal () {
            return this.expenses.reduce((a, b) => a + b.amount, 0)
        }
    },

    methods: {
        async getExpenses () {
            this.isLoading = true
             try {
                 const response = await axios.get('expenses')
                 this.expenses = response.data
             } catch (e) {
                 console.log(e)
             }
             this.isLoading = false
        }
    }
}
</script>
