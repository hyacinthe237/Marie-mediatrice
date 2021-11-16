<template>
    <div>
        <multiselect 
            v-model="value" 
            :options="options" 
            label="name" track-by="id" 
            :multiple="true">
        </multiselect>
        
        <input type="hidden" :name="name" :value="formattedValues">
    </div>
</template>

<script>
import Multiselect from 'vue-multiselect'

export default {
    components: { Multiselect },

    props: {
        name: { type: String, default: 'name' },
        values: { type: Array, default: () => [] },
    },

    data: () => ({
        value: [],
        options: _items
    }),

    mounted () {
        if (this.values.length > 0) {
            this.value = [...this.values]
        }
    },

    computed: {
        formattedValues () {
            return this.value.map(v => v.id).join(',')
        }
    }
}
</script>