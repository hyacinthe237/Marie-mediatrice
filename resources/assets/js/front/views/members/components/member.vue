<template lang="html">
    <div class="link border-bottom" @click="viewMember()">
        <span class="pull-right teal fs-16 bold">{{ contributions | currency }}</span>
        {{ member.name }}
    </div>
</template>

<script>
import _ from 'lodash'

export default {
    props: ['member'],

    computed: {
        contributions () {
            return this.member.entries.reduce((a, b) => a + b.amount, 0)
        }
    },

    methods: {
        viewMember () {
            this.$router.push({ name: 'member', params: { id: this.member.code }})
        }
    }
}
</script>
