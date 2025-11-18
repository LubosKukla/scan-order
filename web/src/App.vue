<template>
  <div
    id="app"
    class="min-h-screen"
    :class="{ 'flex bg-deep text-ink': showAdminShell }"
  >
    <NavBarAdmin v-if="showAdminShell" />
    <div class="flex-1 min-h-screen" :class="{ 'bg-surface text-ink flex flex-col': showAdminShell }">
      <template v-if="showAdminShell">
        <AdminHeaderPanel />
      </template>
      <template v-else>
        <WebHeader v-if="isWebSection" />
      </template>
      <main :class="{ 'flex-1 px-6 py-8': showAdminShell }">
        <router-view />
      </main>
      <AdminFooter v-if="showAdminShell" />
    </div>
    <AdminFooter v-if="showAdminFooter && !showAdminShell" />
  </div>
</template>

<script>
import WebHeader from './components/layout/header/WebHeader.vue';
import NavBarAdmin from './components/layout/header/NavBarAdmin.vue';
import AdminHeaderPanel from './components/layout/header/AdminHeaderPanel.vue';
import AdminFooter from './components/layout/footer/AdminFooter.vue';

export default {
  name: 'AppRoot',
  components: { WebHeader, NavBarAdmin, AdminHeaderPanel, AdminFooter },
  computed: {
    isWebSection() {
      return this.$route.matched.some((r) => r.meta && r.meta.section === 'web');
    },
    showAdminShell() {
      return this.$route.matched.some((r) => r.meta && r.meta.requiresRole === 'admin');
    },
    showAdminFooter() {
      return this.$route.matched.some((r) => r.meta && r.meta.section === 'admin');
    },
  },
};
</script>

<style lang="scss">
:root {
  font-family: 'Open Sans', sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  color: var(--color-ink);
}

#app {
  box-sizing: border-box;
}
</style>
