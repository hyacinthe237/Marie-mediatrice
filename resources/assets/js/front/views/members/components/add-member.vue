<template lang="html">
    <div class="">
        <Modal :title="'Add Member'" :slug="'addMember'">
            <form class="_form" @submit.prevent="save()">
                <div class="form-group">
                    <label>Preferred name (Required)</label>
                    <input type="text"
                        name="name"
                        v-model="ghost.name"
                        placeholder="Preferred name"
                        required
                        class="form-control input-lg input-white">
                </div>

                <div class="form-group">
                    <label>Mobile (Required)</label>
                    <input type="text"
                        class="form-control input-lg input-white"
                        placeholder="Mobile number"
                        v-model="ghost.mobile">
                </div>

                <div class="form-group">
                    <label>First name</label>
                    <input type="text"
                        placeholder="First name"
                        class="form-control input-lg input-white"
                        v-model="ghost.firstname">
                </div>

                <div class="form-group">
                    <label>Last name</label>
                    <input type="text"
                        class="form-control input-lg input-white"
                        placeholder="Last name"
                        v-model="ghost.lastname">
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email"
                        class="form-control input-lg input-white"
                        placeholder="Email"
                        v-model="ghost.email">
                </div>

                <Spinner v-show="isLoading"></Spinner>

                <div class="mt-20">
                    <button type="submit"
                        :disabled="isLoading"
                        class="btn btn-lg btn-block btn-success elevated">
                        <span v-show="!isLoading">SAVE MEMBER</span>
                        <span v-show="isLoading">SAVING...</span>
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
        ghost: {}
    }),

    components: { Modal },

    methods: {
        async save () {
            this.isLoading = true
            
            try {
                const response = await axios.post('members', this.ghost)
                this.$emit('added', response.data)
                this.ghost = {}
                window.$('#addMember').modal('hide')
            }
            catch (e) {
                this.$sweet.show({
                    type: 'error',
                    title: e.response.data
                })
            }

            this.isLoading = false
        }
    }
}
</script>
