<template>
  <div
    id="app"
    class="min-h-screen"
    :class="{ 'flex bg-deep text-ink': showAdminShell }"
  >
    <AdminHeader v-if="showAdminShell" />
    <div :class="showAdminShell ? 'flex-1 min-h-screen bg-surface text-ink' : ''">
      <WebHeader v-if="isWebSection" />
      <router-view />
    </div>
  </div>
</template>

<script>
import WebHeader from './components/layout/header/WebHeader.vue';
import AdminHeader from './components/layout/header/AdminHeader.vue';

export default {
  name: 'AppRoot',
  components: { WebHeader, AdminHeader },
  computed: {
    isWebSection() {
      return this.$route.matched.some((r) => r.meta && r.meta.section === 'web');
    },
    showAdminShell() {
      return this.$route.matched.some((r) => r.meta && r.meta.requiresRole === 'admin');
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
  box-sizing: border-box;
}
</style>
