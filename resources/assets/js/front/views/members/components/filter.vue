<template lang="html">
    <div class="">
        <Modal :title="'Filter members'" :slug="'filterMembers'">
            <form class="_form" @submit.prevent="save()">
                <div class="form-group">
                    <label>Filter by</label>
                    <select class="form-control input-lg input-white" name="role" v-model="ghost.by">
                        <option v-for="r in bys" :value="r.value">{{ r.name }}</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Order</label>
                    <select class="form-control input-lg input-white" name="role" v-model="ghost.order">
                        <option v-for="r in orders" :value="r.value">{{ r.name }}</option>
                    </select>
                </div>

                <div class="mt-40 text-right">
                    <button type="submit"
                        class="btn btn-lg btn-success">
                        <i class="ion-checkmark"></i> Apply filters
                    </button>
                </div>
            </form>
        </Modal>
    </div>
</template>

<script>
import Modal from '../../../components/modal/modal'

export default {
    data: () => ({
        ghost: {
            by: 'name',
            order: 'ascending'
        },

        bys: [],
        orders: []
    }),

    components: { Modal },

    mounted () {
        this.bys = [
            { name: 'Member name', value: 'name' },
            { name: 'Total contributions', value: 'total' }
        ]

        this.orders = [
            { name: 'Ascending', value: 'ascending' },
            { name: 'Descending', value: 'descending' }
        ]
    },

    methods: {
        save () {
            this.$emit('filtered', this.ghost)
            window.$('.modal').modal('hide')
        }
    }
}
</script>
