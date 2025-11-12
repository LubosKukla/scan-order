<template>
  <div id="app">
    <WebHeader v-if="isWebSection" />
    <router-view />
  </div>
</template>

<script>
import WebHeader from './components/layout/header/WebHeader.vue';

export default {
  name: 'AppRoot',
  components: { WebHeader },
  computed: {
    isWebSection() {
      return this.$route.matched.some((r) => r.meta && r.meta.section === 'web');
    },
  },
};
</script>

<style lang="scss">
#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  color: #2c3e50;
}
</style>
