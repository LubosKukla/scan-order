<template>
  <!-- Mobile top bar -->
  <header class="md:hidden sticky top-0 z-50 bg-deep text-white shadow-lg shadow-black/20">
    <div class="flex items-center justify-between px-4 py-3">
      <div class="flex items-center gap-3">
        <img src="@/assets/img/only-logo.svg" alt="Scan&Order logo" class="h-10 w-auto select-none" />
        <span class="text-base font-semibold">Admin</span>
      </div>
      <button
        type="button"
        class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/10 hover:bg-white/20 transition"
        @click="toggleMobile"
        aria-label="Otvoriť navigáciu"
      >
        <font-awesome-icon :icon="mobileOpen ? faChevronRight : faBars" />
      </button>
    </div>
    <transition name="fade">
      <div v-if="mobileOpen" class="fixed inset-0 z-50 bg-black/40 backdrop-blur-sm" @click.self="toggleMobile(false)">
        <div class="bg-deep text-ink w-[80%] max-w-xs h-full shadow-xl p-4 gap-2 flex flex-col">
          <div class="flex items-center justify-between pb-4 border-b border-ink/20">
            <div class="flex items-center gap-2">
              <img src="@/assets/img/only-logo.svg" alt="Scan&Order logo" class="h-8 w-auto select-none" />
              <span class="font-semibold text-white">Admin</span>
            </div>
            <button
              type="button"
              class="text-ink hover:text-white transition"
              aria-label="Zatvoriť navigáciu"
              @click="toggleMobile(false)"
            >
              <font-awesome-icon :icon="faChevronRight" />
            </button>
          </div>
          <nav class="space-y-1 overflow-y-auto flex-1 pr-1" aria-label="Admin navigacia mobil">
            <router-link
              v-for="item in navLinksTop"
              :key="`mobile-${item.routeName}`"
              :to="{ name: item.routeName }"
              class="flex items-center rounded-xl px-3 py-2 text-sm font-medium transition"
              :class="linkClasses(item.routeName)"
              @click="toggleMobile(false)"
            >
              <span
                class="flex h-9 w-9 items-center justify-center rounded-lg text-base"
                :class="iconClasses(item.routeName)"
              >
                <font-awesome-icon :icon="item.icon" />
              </span>
              <span class="ml-3 truncate">{{ item.label }}</span>
            </router-link>

            <div v-if="hasDivider" class="w-full h-0 border-b border-ink/15 my-2"></div>

            <router-link
              v-for="item in navLinksBottom"
              :key="`mobile-${item.routeName}`"
              :to="{ name: item.routeName }"
              class="flex items-center rounded-xl px-3 py-2 text-sm font-medium transition"
              :class="linkClasses(item.routeName)"
              @click="toggleMobile(false)"
            >
              <span
                class="flex h-9 w-9 items-center justify-center rounded-lg text-base"
                :class="iconClasses(item.routeName)"
              >
                <font-awesome-icon :icon="item.icon" />
              </span>
              <span class="ml-3 truncate">{{ item.label }}</span>
            </router-link>
          </nav>
        </div>
      </div>
    </transition>
  </header>

  <!-- Desktop sidebar -->
  <aside
    class="hidden md:flex bg-deep text-ink flex-col border-r border-ink/10 shadow-xl shadow-black/20 transition-all duration-300 ease-in-out"
    :class="collapsed ? 'w-20' : 'w-64'"
  >
    <div class="flex items-center gap-3 px-5 py-6 border-b border-ink/10">
      <div class="h-auto w-auto flex items-center justify-center rounded-2xl">
        <img src="@/assets/img/only-logo.svg" alt="Scan&Order logo" class="w-auto max-h-14 select-none" />
      </div>
      <div v-if="!collapsed" class="flex flex-col leading-tight">
        <span class="text-xs uppercase tracking-widest text-ink/70">Scan&Order</span>
        <span class="text-lg font-semibold text-white">Admin</span>
      </div>
    </div>

    <nav class="flex-1 px-3 py-5 space-y-2" aria-label="Admin navigacia">
      <router-link
        v-for="item in navLinksTop"
        :key="item.routeName"
        :to="{ name: item.routeName }"
        class="flex items-center rounded-xl text-sm font-medium transition-all duration-200 focus-visible:ring-2 focus-visible:ring-primary focus-visible:outline-none"
        :class="linkClasses(item.routeName)"
      >
        <span
          class="flex h-10 w-10 items-center justify-center rounded-xl text-base transition-colors duration-200"
          :class="isActive(item.routeName) ? 'bg-white/20 text-white' : 'bg-white/5 text-ink group-hover:text-white'"
        >
          <font-awesome-icon :icon="item.icon" />
        </span>
        <span
          v-if="!collapsed"
          class="ml-3 truncate text-ink group-hover:text-white"
          :class="{ 'text-white': isActive(item.routeName) }"
        >
          {{ item.label }}
        </span>
      </router-link>
      <div v-if="hasDivider" class="w-full mt-6 h-0 border-b border-ink/10"></div>
      <router-link
        v-for="item in navLinksBottom"
        :key="item.routeName"
        :to="{ name: item.routeName }"
        class="flex items-center rounded-xl text-sm font-medium transition-all duration-200 focus-visible:ring-2 focus-visible:ring-primary focus-visible:outline-none"
        :class="linkClasses(item.routeName)"
      >
        <span
          class="flex h-10 w-10 items-center justify-center rounded-xl text-base transition-colors duration-200"
          :class="isActive(item.routeName) ? 'bg-white/20 text-white' : 'bg-white/5 text-ink group-hover:text-white'"
        >
          <font-awesome-icon :icon="item.icon" />
        </span>
        <span
          v-if="!collapsed"
          class="ml-3 truncate text-ink group-hover:text-white"
          :class="{ 'text-white': isActive(item.routeName) }"
        >
          {{ item.label }}
        </span>
      </router-link>
    </nav>
    <button
      type="button"
      class="mt-auto cursor-pointer sticky bottom-0 flex items-center justify-center border-t border-ink/10 bg-deep px-4 py-4 text-ink/70 transition-colors cursor-pointer duration-200 hover:text-white"
      @click="toggleCollapse"
      aria-label="Prepnut zobrazenie navigacie"
    >
      <font-awesome-icon :icon="collapsed ? faChevronRight : faChevronLeft" />
    </button>
  </aside>
</template>

<script>
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import {
  faBars,
  faChartLine,
  faChevronLeft,
  faChevronRight,
  faClipboardList,
  faGaugeHigh,
  faGear,
  faHandsHelping,
  faIdBadge,
  faQrcode,
  faStar,
  faTicket,
  faUtensils,
  faUsers,
} from '@fortawesome/free-solid-svg-icons';

export default {
  name: 'NavBarAdmin',
  components: { FontAwesomeIcon },
  data() {
    return {
      collapsed: false,
      mobileOpen: false,
      navItems: [
        { label: 'Dashboard', routeName: 'admin-prehlad', icon: faGaugeHigh },
        { label: 'Štatistiky', routeName: 'admin-statistiky', icon: faChartLine },
        { label: 'Menu', routeName: 'admin-sprava-menu', icon: faUtensils },
        { label: 'Objednávky', routeName: 'admin-objednavky', icon: faClipboardList },
        { label: 'Recenzie', routeName: 'admin-recenzie', icon: faStar },
        { label: 'Zákazníci', routeName: 'admin-zakaznici', icon: faUsers },
        { label: 'Zamestnanci', routeName: 'admin-zamestnanci', icon: faIdBadge },
        { label: 'QR kódy', routeName: 'admin-qr-kody', icon: faQrcode },
        { type: 'divider', key: 'nav-divider-main' },
        { label: 'Predplatné', routeName: 'admin-predplatne', icon: faTicket },
        { label: 'Pomoc a podpora', routeName: 'admin-pomoc-a-podpora', icon: faHandsHelping },
        { label: 'Nastavenia', routeName: 'admin-nastavenia', icon: faGear },
      ],
      faBars,
      faChevronLeft,
      faChevronRight,
    };
  },
  computed: {
    dividerIndex() {
      return this.navItems.findIndex((item) => item.type === 'divider');
    },
    hasDivider() {
      return this.dividerIndex !== -1;
    },
    navLinksTop() {
      if (!this.hasDivider) return this.navItems.filter((item) => !item.type);
      return this.navItems.slice(0, this.dividerIndex).filter((item) => !item.type);
    },
    navLinksBottom() {
      if (!this.hasDivider) return [];
      return this.navItems.slice(this.dividerIndex + 1).filter((item) => !item.type);
    },
  },
  mounted() {
    if (typeof window !== 'undefined') {
      window.addEventListener('resize', this.handleResize);
    }
  },
  beforeUnmount() {
    if (typeof window !== 'undefined') {
      window.removeEventListener('resize', this.handleResize);
    }
  },
  methods: {
    handleResize() {
      if (typeof window !== 'undefined' && window.innerWidth >= 768 && this.mobileOpen) {
        this.toggleMobile(false);
      }
    },
    iconClasses(routeName) {
      return this.isActive(routeName) ? 'bg-white/20 text-white' : 'bg-white/5 text-ink group-hover:text-white';
    },
    toggleMobile(state) {
      const next = typeof state === 'boolean' ? state : !this.mobileOpen;
      this.mobileOpen = next;
      if (typeof document !== 'undefined' && document.body) {
        document.body.classList.toggle('overflow-hidden', next);
      }
    },
    toggleCollapse() {
      this.collapsed = !this.collapsed;
    },
    isActive(routeName) {
      return this.$route.name === routeName;
    },
    linkClasses(routeName) {
      const base = [
        'group',
        this.collapsed ? 'justify-center px-0 py-2' : 'px-2 py-2 gap-2',
        this.isActive(routeName)
          ? 'bg-primary text-white shadow-lg shadow-primary/10'
          : 'text-ink hover:bg-white/5 hover:text-white',
      ];
      return base;
    },
  },
};
</script>

<style lang="scss" scoped>
/* Hide scrollbar for Chrome, Safari and Opera */
nav::-webkit-scrollbar {
  display: none;
}

/* Hide scrollbar for IE, Edge and Firefox */
nav {
  -ms-overflow-style: none; /* IE and Edge */
  scrollbar-width: none; /* Firefox */
}
</style>
