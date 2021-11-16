<template lang="html">
    <div class="">
        <Modal :title="'Edit Member'" :slug="'editMember'">
            <form class="_form" @submit.prevent="update()">
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

                <hr>

                <div class="form-group">
                    <label>Position</label>
                    <input type="text"
                        class="form-control input-lg input-white"
                        placeholder="Member position"
                        v-model="ghost.position">
                </div>

                <div class="form-group">
                    <label>Role</label>
                    <select class="form-control input-lg input-white" name="role" v-model="ghost.role_id">
                        <option v-for="r in roles" :value="r.id">{{ r.name }}</option>
                    </select>
                </div>

                <Spinner v-show="isLoading"></Spinner>

                <div class="mt-20">
                    <button type="submit"
                        :disabled="isLoading"
                        class="btn btn-lg btn-block btn-success elevated">
                        <span v-show="!isLoading">UPDATE MEMBER</span>
                        <span v-show="isLoading">UPDATING...</span>
                    </button>
                </div>
            </form>
        </Modal>
    </div>
</template>

<script>
import _ from 'lodash'
import Modal from '../../../components/modal/modal'

export default {
    props: ['member', 'roles'],

    data: () => ({
        ghost: {}
    }),

    components: { Modal },

    mounted () {
        this.ghost = _.cloneDeep(this.member)
    },

    watch: {
        member (val) {
            this.ghost = _.cloneDeep(val)
        }
    },

    methods: {
        async update () {
            this.isLoading = true

            try {
                const response = await axios.put('members', this.ghost)
                this.$emit('updated', response.data)
                window.$('#editMember').modal('hide')
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
