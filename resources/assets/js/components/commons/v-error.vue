<template lang="html">
    <span class="_error-message" v-show="err.has(name) && show">{{ err.first(name) }}</span>
</template>

<script>
export default {
    props: {
        name: {
            type: String,
            default: ''
        },

        show: {
            type: Boolean,
            default: true
        },

        err: {
            type: Object,
            default: () => {}
        }
    }
}
</script>
