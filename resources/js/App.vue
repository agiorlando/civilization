<script lang="ts">
import { defineComponent } from 'vue';
import Navigation from './components/Navigation.vue';

export default defineComponent({
  name: 'App',
  components: {
    Navigation,
  },
});
</script>

<template>
  <div>
    <Navigation />
    <router-view></router-view>
  </div>
</template>

<style scoped>
</style>
