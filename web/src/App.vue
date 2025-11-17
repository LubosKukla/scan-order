<template>
  <div
    id="app"
    class="min-h-screen"
    :class="{ 'flex bg-deep text-ink': showAdminShell }"
  >
    <NavBarAdmin v-if="showAdminShell" />
    <div
      class="flex-1 min-h-screen"
      :class="{ 'bg-surface text-ink flex flex-col': showAdminShell }"
    >
      <WebHeader v-if="isWebSection" />
      <main :class="{ 'flex-1 px-6 py-8': showAdminShell }">
        <router-view />
      </main>
      <AdminFooter v-if="showAdminShell" />
    </div>
  </div>
</template>

<script>
import WebHeader from './components/layout/header/WebHeader.vue';
import NavBarAdmin from './components/layout/header/NavBarAdmin.vue';
import AdminFooter from './components/layout/footer/AdminFooter.vue';

export default {
  name: 'AppRoot',
  components: { WebHeader, NavBarAdmin, AdminFooter },
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
