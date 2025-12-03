<template>
  <div id="app" class="min-h-screen" :class="{ 'bg-deep text-ink flex flex-col md:flex-row': showAdmin }">
    <NavBarAdmin v-if="showAdmin" />
    <div class="flex-1 min-h-screen" :class="{ 'bg-surface text-ink flex flex-col': showAdmin }">
      <AdminWebBanner v-if="!showAdmin && isAuthenticated" />
      <template v-if="showAdmin">
        <AdminHeaderPanel />
      </template>
      <template v-else-if="showRestauracia">
        <RestaurantHeader />
      </template>
      <template v-else-if="showInMore">
        <WebHeader />
      </template>
      <main :class="{ 'flex-1 px-6 py-8': showAdmin }" class="bg-ink">
        <router-view />
      </main>
      <template v-if="showAdmin">
        <AdminFooter />
      </template>
      <template v-else-if="showInMore">
        <WebFooter />
      </template>
    </div>
  </div>
  <SnackbarStack />
</template>

<script>
import WebHeader from './components/layout/header/WebHeader.vue';
import NavBarAdmin from './components/layout/header/NavBarAdmin.vue';
import AdminHeaderPanel from './components/layout/header/AdminHeaderPanel.vue';
import AdminFooter from './components/layout/footer/AdminFooter.vue';
import WebFooter from './components/layout/footer/WebFooter.vue';
import SnackbarStack from './components/global/feedback/SnackbarStack.vue';
import AdminWebBanner from './components/layout/header/AdminWebBanner.vue';
import RestaurantHeader from './components/layout/header/RestaurantHeader.vue';

export default {
  name: 'AppRoot',
  components: {
    WebHeader,
    NavBarAdmin,
    AdminHeaderPanel,
    AdminFooter,
    WebFooter,
    SnackbarStack,
    AdminWebBanner,
    RestaurantHeader,
  },
  computed: {
    matchedRoutes() {
      return this.$route?.matched || [];
    },
    isWebSection() {
      return this.matchedRoutes.some((r) => r.meta && r.meta.section === 'web');
    },
    showAdmin() {
      return this.matchedRoutes.some((r) => r.meta && r.meta.requiresRole === 'admin');
    },
    showRestauracia() {
      return this.matchedRoutes.some((r) => r.meta && r.meta.section === 'restauracia');
    },
    showInMore() {
      if (this.showAdmin) return false;
      const hasMore = this.matchedRoutes.some((r) => r.meta && r.meta.showInMore);
      return this.isWebSection || hasMore;
    },
    isAuthenticated() {
      return this.$store.getters['user/isLoggedIn'];
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

button,
a {
  cursor: pointer;
}
</style>
