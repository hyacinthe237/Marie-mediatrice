<template lang="html">
    <section class="_view-auth">
        <div class="mobile-container">
            <div class="_overlay"></div>

            <div class="mobile-inner">
                <div class="auth-logo">
                    CAA Sydney
                </div>

                <h4 class="white text-center">Member Login</h4>

                <div class="auth-form mt-40">
                    <form class="_form" @submit.prevent="authenticate()">
                        <div class="form-group">
                            <label>Enter Mobile</label>

                            <div class="inner-addon left-addon">
                                <i class="ion-android-call"></i>

                                <input type="number" name="mobile"
                                    class="form-control input-lg"
                                    placeholder="0422334455"
                                    v-model="ghost.mobile"
                                    required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Enter PIN</label>

                            <div class="inner-addon left-addon">
                                <i class="ion-android-lock"></i>

                                <input type="password" name="pin"
                                    class="form-control input-lg"
                                    placeholder="Enter PIN"
                                    v-model="ghost.pin"
                                    required>
                            </div>
                        </div>


                        <div class="mt-40 text-center">
                            <z-btn elevated block :disabled="isLoading" :colour="'success'">
                                <span v-show="!isLoading">
                                    <i class="ion-checkmark"></i>
                                    Sign In
                                </span>

                                <span v-show="isLoading">
                                    Authenticating...
                                </span>
                            </z-btn>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
export default {
    name: 'login',

    data: () => ({
        ghost: {},
        isLoading: false
    }),

    methods: {
        async authenticate () {
            this.isLoading = true
            let axio = axios.create({ baseURL: '/', timeout: 1000 })

            try {
                await axio.post('signin', this.ghost)
                location.reload()
            } catch (e) {
                this.$sweet.show({
                    type: 'error',
                    title: e.response.data
                })
            }
            this.isLoading = false
        }
    }
}
</script>
