<template lang="html">
    <section class="_navbar">
        <div class="container">
            <div
                class="_navbar-icon _navbar-left-icon"
                v-show="backButton"
                @click="navigateBack()">
                <i class="ion-android-arrow-back"></i>
            </div>

            <div class="_navbar-icon _navbar-right-icon">
                <slot></slot>
            </div>

            <div class="title">
                 {{ title }}
            </div>
        </div>
    </section>
</template>

<script>
export default {
    name: 'navbar',
    props: {
        title: {
            type: String,
            default: 'Home'
        },
        backButton: {
            type: Boolean,
            default: false
        }
    },

    methods: {
        navigateBack () {
            window.history.back()
        }
    }
}
</script>
