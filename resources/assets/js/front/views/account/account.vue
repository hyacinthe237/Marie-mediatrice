<template lang="html">
    <section class="_page">
        <Navbar :title="'My Account'"></Navbar>

        <div class="container">
            <div class="text-right mt-10">
                <button class="btn btn-primary elevated" @click="goto('pin')">
                    <i class="ion-locked"></i> Change PIN
                </button>

                <button class="btn btn-teal elevated" @click="signout()">
                    <i class="ion-power"></i> Sign Out
                </button>
            </div>

            <form class="_form mt-20" @submit.prevent="update()">
                <div class="form-group">
                    <label>Preferred name</label>
                    <input type="text" required readonly
                        class="form-control input-lg input-white"
                        v-model="ghost.name">
                </div>

                <div class="form-group">
                    <label>First name</label>
                    <input type="text" readonly
                        class="form-control input-lg input-white"
                        v-model="ghost.firstname">
                </div>

                <div class="form-group">
                    <label>Last name</label>
                    <input type="text" readonly
                        class="form-control input-lg input-white"
                        v-model="ghost.lastname">
                </div>

                <div class="form-group">
                    <label>Mobile</label>
                    <input type="text"
                        class="form-control input-lg input-white"
                        v-model="ghost.mobile">
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email"
                        class="form-control input-lg input-white"
                        v-model="ghost.email">
                </div>

                <Spinner v-show="isLoading"></Spinner>

                <div class="text-center mt-20 pb-20">
                    <z-btn elevated :size="'lg'" :colour="'wise'"
                        :disabled="isLoading"
                        @click="update()">
                        <span v-show="isLoading">UPDATING...</span>
                        <span v-show="!isLoading">UPDATE DETAILS</span>
                    </z-btn>
                </div>
            </form>
        </div>

    </section>
</template>

<script>
import _ from 'lodash'

export default {
    name: 'account',
    data: () => ({
        ghost: {},
        isLoading: false
    }),

    mounted () {
        this.ghost = _.cloneDeep(this.user)
    },

    watch: {
        user (val) {
            this.ghost = _.cloneDeep(val)
        }
    },

    computed: {
        user () {
            return this.$store.state.users.user
        }
    },

    methods: {
        async signout () {
            let axio = axios.create({ baseURL: '/', timeout: 1000 })

            try {
                await axio.get('signout')
                window.location.href = "/"
            } catch (e) {
                console.log(e)
            }
        },

        async update () {
            this.isLoading = true
            try {
                const response = await axios.put('/account', this.ghost)
                this.$store.commit('users/SET_USER', response.data)
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
