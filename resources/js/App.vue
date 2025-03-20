<script>
import Navigation from './components/Navigation.vue';

export default {
    name: 'App',
    components: {
        Navigation,
    },
}
</script>

<template>
    <div>
        <Navigation />
        <router-view></router-view>
    </div>
</template>

<style scoped>
</style>
