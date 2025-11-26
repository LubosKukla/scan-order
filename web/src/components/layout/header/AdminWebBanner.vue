<template>
  <div v-if="isAuthenticated" class="bg-deep text-white text-sm border-b border-white/10">
    <div class="max-w-6xl mx-auto px-4 py-2 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
      <span class="font-semibold tracking-tight">Prihlásený ako reštaurácia</span>
      <div class="flex items-center gap-3 text-xs sm:text-sm">
        <span class="truncate max-w-[220px]" :title="userEmail">{{ userEmail }}</span>
        <router-link
          :to="{ name: 'admin-prehlad' }"
          class="inline-flex items-center gap-2 rounded-md bg-white/15 hover:bg-white/25 px-3 py-1 transition"
        >
          <span>Prejsť do administrácie</span>
        </router-link>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'AdminWebBanner',
  computed: {
    isAuthenticated() {
      return this.$store.getters['user/isLoggedIn'];
    },
    userEmail() {
      return this.$store.getters['user/currentUser']?.user.email || '—';
    },
  },
};
</script>
