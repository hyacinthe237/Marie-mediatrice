<template lang="html">
    <section class="_page">
        <Navbar :title="'About'" backButton></Navbar>

        <div class="container">
            <Spinner v-show="isLoading"></Spinner>

            <h5 class="bold">About the association</h5>
            Coming soon...
        </div>

    </section>
</template>

<script>
export default {
    name: 'about',
    data () {
        return {
            organization: {},
            isLoading: false
        }
    },

    mounted () {
        // this.getOrganization()
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
