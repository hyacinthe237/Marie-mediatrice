<template lang="html">
    <div class="_core">
        <div class="">
            <transition name="">
                <router-view></router-view>
            </transition>
        </div>

        <Toolbar></Toolbar>
    </div>
</template>

<script>
import Nav from './components/nav/nav'
import Toolbar from './components/footer/toolbar'

export default {
    name: 'core',
    components: { NavComponent: Nav, Toolbar },

    mounted () {
        this.$store.dispatch('users/getUser')
    }
}
</script>

<style scoped>
.fade-enter-active, .fade-leave-active {
    transition-property: opacity;
    transition-duration: .25s;
}

.fade-enter-active {
    transition-delay: .25s;
}

.fade-enter, .fade-leave-active {
    opacity: 0
}
</style>
