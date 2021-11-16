<template lang="html">
    <div class="text-center mt-10">
        <vue-simple-spinner
            :size="50"
            :line-fg-color="'#168C6A'"
            :line-size="3">
        </vue-simple-spinner>
    </div>
</template>

<script>
import Spinner from 'vue-simple-spinner'

export default {
    name: 'spinner',
    components: {
        Spinner
    }
}
</script>
