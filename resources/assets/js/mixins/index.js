Vue.mixin({
    data: () => ({
        isLoading: false
    }),

    computed: {
        user () {
            return this.$store.state.users.user
        },

        canManageMembers () {
            return this.can('create_members')
        },

        canManageEvents () {
            return this.can('manage_events')
        },

        canManageFunds () {
            return this.can('manage_funds')
        },

        canManageExpenses () {
            return this.can('manage_expenses')
        },

        canManageEntries () {
            return this.can('manage_entries')
        },

        canEditOrganization () {
            return this.can('edit_organization')
        }
    },

    methods: {
        can (permission) {
            if (this.user.role) {
                return this.user.role.authorizations.filter(a => a.slug === permission).length > 0
            }
            return false
        }
    }
})
