<template lang="html">
    <div class="_toolbar">
        <div class="container">
            <div class="row">
                <div class="col-xs-3">
                    <div class="_toolbar-tab" @click="goto('home')">
                        <div class="icon">
                            <i class="ion-android-home"></i>
                        </div>
                        Home
                    </div>
                </div>

                <div class="col-xs-3">
                    <div class="_toolbar-tab" @click="goto('events')">
                        <div class="icon">
                            <i class="ion-calendar"></i>
                        </div>
                        Events
                    </div>
                </div>

                <div class="col-xs-3">
                    <div class="_toolbar-tab" @click="goto('userfinances')">
                        <div class="icon">
                            <i class="ion-cash"></i>
                        </div>
                        Finances
                    </div>
                </div>

                <div class="col-xs-3">
                    <div class="_toolbar-tab" @click="goto('account')">
                        <div class="icon">
                            <i class="ion-android-person"></i>
                        </div>
                        Account
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'toolbar',

    methods: {
        goto (name) {
            this.$router.push({ name })
        }
    }
}
</script>
