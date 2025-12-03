<template>
  <header class="sticky top-0 z-50 bg-deep text-white shadow">
    <div class="mx-auto max-w-6xl px-4">
      <div class="flex h-16 items-center gap-3">
        <!-- Brand -->
        <router-link to="/" class="items-center gap-3 flex-1" aria-label="Domov">
          <img src="@/assets/img/logo-scan-order.svg" alt="ScanOrder logo" class="w-40 select-none h-auto" />
        </router-link>

        <!-- Desktop nav -->
        <nav class="main-nav ml-auto hidden items-center gap-8 md:flex-3 md:flex md:justify-center"
          aria-label="Hlavná navigácia">
          <div class="w-16 flex justify-center">
            <router-link to="/o-nas" class="text-ink font-normal hover:font-semibold hover:scale-105 duration-50">
              O nás
            </router-link>
          </div>
          <div class="w-32 flex justify-center">
            <router-link to="/ako-to-funguje"
              class="text-ink font-normal hover:font-semibold hover:scale-105 duration-50">
              Ako to funguje
            </router-link>
          </div>
          <div class="w-16 flex justify-center">
            <router-link to="/cennik" class="text-ink font-normal hover:font-semibold hover:scale-105 duration-50">
              Cenník
            </router-link>
          </div>
        </nav>

        <!-- Right side actions (desktop) -->
        <div class="hidden items-center gap-3 md:flex-1 md:flex md:justify-end">
          <router-link to="/kontakt"
            class="inline-flex items-center gap-2 rounded-full bg-ink px-3 py-2 font-bold text-deep">
            Kontakt
          </router-link>
        </div>

        <!-- Mobile toggles -->
        <div @click="mobileOpen = !mobileOpen"
          class="cursor-pointer flex-1 md:hidden inline-flex items-center justify-center">
          <button class="ml-auto rounded-md p-2 text-ink ring-1 ring-ink/30" aria-label="Menu">
            <svg v-if="!mobileOpen" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-6 w-6 fill-current">
              <path d="M3 6h18v2H3zm0 5h18v2H3zm0 5h18v2H3z" />
            </svg>
            <svg v-else xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-6 w-6 fill-current">
              <path d="M5.3 6.3 6.7 5l12 12-1.4 1.3-12-12z" />
              <path d="m18.7 6.3-1.4-1.3-12 12 1.4 1.3 12-12z" />
            </svg>
          </button>
        </div>
      </div>
    </div>

    <!-- Mobile panel -->
    <div v-show="mobileOpen" class="md:hidden border-t border-deep bg-deep text-ink">
      <div class="mx-auto max-w-6xl px-4 py-4 space-y-3">
        <nav class="mobile-nav flex flex-col gap-2" aria-label="Hlavná navigácia mobil">
          <router-link to="/o-nas"
            class="block px-3 py-3 text-left text-ink font-normal hover:font-semibold hover:opacity-80 hover:scale-105 duration-150 transition-colors">
            O nás
          </router-link>
          <router-link to="/ako-to-funguje"
            class="block px-3 py-3 text-left text-ink font-normal hover:font-semibold hover:opacity-80 hover:scale-105 duration-150 transition-colors">
            Ako to funguje
          </router-link>
          <router-link to="/cennik"
            class="block px-3 py-3 text-left text-ink font-normal hover:font-semibold hover:opacity-80 hover:scale-105 duration-150 transition-colors">
            Cenník
          </router-link>
          <router-link to="/kontakt"
            class="block px-3 py-3 text-left text-ink font-normal hover:font-semibold hover:opacity-80 hover:scale-105 duration-150 transition-colors">
            Kontakt
          </router-link>
        </nav>
      </div>
    </div>
  </header>
</template>

<script>
export default {
  name: 'WebHeader',
  data() {
    return { mobileOpen: false };
  },
  watch: {
    $route() {
      this.mobileOpen = false;
    },
  },
  computed: {
    isAdmin() {
      try {
        return localStorage.getItem('role') === 'admin';
      } catch {
        return false;
      }
    },
    adminRoute() {
      return this.isAdmin ? { name: 'admin-prehlad' } : { name: 'admin-login' };
    },
  },
  methods: {},
};
</script>

<style scoped>
.main-nav a.router-link-active,
.main-nav a.router-link-exact-active,
.mobile-nav a.router-link-active,
.mobile-nav a.router-link-exact-active {
  font-weight: 900;
}
</style>
