<template>
  <div id="app" class="min-h-screen" :class="{ 'flex bg-deep text-ink': showAdmin }">
    <NavBarAdmin v-if="showAdmin" />
    <div class="flex-1 min-h-screen" :class="{ 'bg-surface text-ink flex flex-col': showAdmin }">
      <template v-if="showAdmin">
        <AdminHeaderPanel />
      </template>
      <template v-else>
        <WebHeader v-if="isWebSection" />
      </template>
      <main :class="{ 'flex-1 px-6 py-8': showAdmin }">
        <router-view />
      </main>
      <AdminFooter v-if="showAdmin" />
    </div>
  </div>
</template>

<script>
import WebHeader from './components/layout/header/WebHeader.vue';
import NavBarAdmin from './components/layout/header/NavBarAdmin.vue';
import AdminHeaderPanel from './components/layout/header/AdminHeaderPanel.vue';
import AdminFooter from './components/layout/footer/AdminFooter.vue';

export default {
  name: 'AppRoot',
  components: {
    WebHeader,
    NavBarAdmin,
    AdminHeaderPanel,
    AdminFooter,
  },
  computed: {
    isWebSection() {
      const matched = this.$route?.matched || [];
      return matched.some((r) => r.meta && r.meta.section === 'web');
    },
    showAdmin() {
      const matched = this.$route?.matched || [];
      return matched.some((r) => r.meta && r.meta.requiresRole === 'admin');
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
