<template lang="html">
    <section class="_page">
        <Navbar :title="fund.name" backButton></Navbar>

        <div class="container">
            <Spinner v-show="isLoading"></Spinner>
            <label class="teal bold">Total</label>
            <h5 class="bold margin-0">{{ entriesTotal | currency }}</h5>
        </div>

        <div class="links bg-white mt-20">
            <div class="link border-bottom" v-for="e in entries">
                <div class="bold">
                    <span class="pull-right">{{ e.amount | currency }}</i></span>
                    {{ e.member.name }}
                </div>

                <div class="teal fs-14">
                    {{ e.made_on | date }}
                </div>
            </div>
        </div>

    </section>
</template>

<script>
export default {
    data () {
        return {
            fund: {},
            entries: [],
            isLoading: false
        }
    },

    mounted () {
        this.getFund()
    },

    computed: {
        entriesTotal () {
            return this.entries.reduce((a, b) => a + b.amount, 0)
        },

        user () {
            return this.$store.state.users.user
        },

        canEdit () {
            if (this.user.role) {
                return this.user.role.authorizations.filter(a => a.slug === 'manage_expenses').length > 0
            }
        }
    },

    methods: {
        async getFund () {
            this.isLoading = true
             try {
                 const response = await axios.get(`funds/${ this.$route.params.id }`)
                 this.fund = response.data
                 this.entries = this.fund.entries
             } catch (e) {
                 console.log(e)
             }
             this.isLoading = false
        }
    }
}
</script>
