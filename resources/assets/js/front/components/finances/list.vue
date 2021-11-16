<template lang="html">
    <div class="links bg-white">
        <div class="link border-bottom" v-for="e in entries">
            <span class="pull-right teal bold fs-14">{{ e.amount | currency }}</span>
            <span class="bold">{{ e.fund.name }}</span>
            <div class="fs-15 teal">
                {{ e.made_on | date }}
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['entry']
}
</script>
