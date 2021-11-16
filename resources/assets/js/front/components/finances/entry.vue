<template lang="html">
    <div class="link border-bottom">
        <span class="pull-right teal bold fs-14">{{ entry.amount | currency }}</span>
        <span class="bold">{{ fund.name }}</span>
        <div class="fs-15 teal">
            {{ entry.made_on | date }}
        </div>
    </div>
</template>

<script>
export default {
    props: {
        entry: {
            required: true
        },

        top: {
            type: Boolean,
            default: false
        }
    },

    computed: {
        fund () {
            return this.entry.fund
        }
    }
}
</script>
