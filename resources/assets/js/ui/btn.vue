<template lang="html">
    <button class="btn" @click="clicked()"
        :disabled="disabled"
        :class="[block ? 'btn-block' : '', elevated ? 'elevated' : '', btnColour, btnSize, round ? 'btn-round' : '']">
        <slot></slot>
    </button>
</template>

<script>
export default {
    name: 'z-btn',
    props: {
        colour: {
            type: String,
            default: 'presumi',
        },

        block: {
            type: Boolean,
            default: false
        },

        disabled: {
            type: Boolean,
            default: false
        },

        elevated: {
            type: Boolean,
            default: false
        },

        size: {
            type: String,
            default: 'lg',
            validator: value => {
                return ['lg', 'sm','md'].includes(value)
            }
        },

        round: {
            type: Boolean,
            default: false
        }
    },

    data: () => ({
        btnColour: '',
        btnSize: ''
    }),

    mounted () {
        this.btnColour = 'btn-' + this.colour
        this.btnSize = 'btn-' + this.size
    },

    methods: {
        clicked () {
            this.$emit('clicked')
        }
    }
}
</script>
