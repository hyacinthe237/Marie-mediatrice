<template lang="html">
    <section class="_page">
        <Navbar :title="'Change PIN'" backButton ></Navbar>

        <div class="container mt-10">
            <form class="_form" @submit.prevent="update()">
                <div class="form-group">
                    <label>Current PIN</label>
                    <input type="password" required
                        class="form-control input-lg input-white text-center"
                        placeholder="Current PIN"
                        v-model="ghost.current">
                </div>

                <div class="form-group">
                    <label>New PIN (minimum 4 digits)</label>
                    <input type="password" required
                        class="form-control input-lg input-white text-center"
                        placeholder="New PIN"
                        v-model="ghost.pin">
                </div>

                <div class="form-group">
                    <label>Confirm PIN</label>
                    <input type="password" required
                        class="form-control input-lg input-white text-center"
                        placeholder="Confirm PIN"
                        v-model="ghost.pin_confirmation">
                </div>

                <Spinner v-show="isLoading"></Spinner>

                <div class="text-center mt-20 pb-20">
                    <z-btn elevated :size="'lg'" :colour="'wise'"
                        :disabled="isLoading"
                        @click="update()">
                        <span v-show="isLoading">SAVING...</span>
                        <span v-show="!isLoading">CHANGE PIN</span>
                    </z-btn>
                </div>
            </form>

        </div>

    </section>
</template>

<script>
import _ from 'lodash'

export default {
    data: () => ({
        ghost: {},
        isLoading: false
    }),

    methods: {
        async update () {
            this.isLoading = true
            try {
                await axios.post('/account/pin', this.ghost)
            } catch (e) {
                console.log(e)
            }
            this.isLoading = false
        }
    }
}
</script>
