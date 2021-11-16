<template lang="html">
    <div class="link border-bottom bold" @click="goto()">
        <span class="pull-right bold">{{ total | currency }}</i></span>
        {{ fund.name }}
    </div>
</template>

<script>
export default {
    props: ['fund'],

    computed: {
        total () {
            if (this.fund.entries) {
                return this.fund.entries.reduce((a, b) => a + b.amount, 0)
            } return 0
        }
    },

    methods: {
        goto () {
            this.$router.push({ name: 'fund', params: { id: this.fund.code } })
        }
    }
}
</script>
