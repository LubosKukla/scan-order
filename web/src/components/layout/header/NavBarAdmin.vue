<template>
  <aside
    class="bg-deep text-ink flex flex-col border-r border-ink/10 shadow-xl shadow-black/20 transition-all duration-300 ease-in-out"
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
      <template v-for="item in navItems">
        <router-link
          v-if="!item.type"
          :key="item.routeName"
          :to="{ name: item.routeName }"
          class="flex items-center rounded-xl text-sm font-medium transition-all duration-200 focus-visible:ring-2 focus-visible:ring-primary focus-visible:outline-none"
          :class="[linkClasses(item.routeName)]"
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
        <div v-else :key="`divider-${item.key}`" class="w-full mt-6 h-0 border-b border-ink/10"></div>
      </template>
    </nav>

    <button
      type="button"
      class="mt-auto sticky bottom-0 flex items-center justify-center border-t border-ink/10 bg-deep px-4 py-4 text-ink/70 transition-colors cursor-pointer duration-200 hover:text-white"
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
      faChevronLeft,
      faChevronRight,
    };
  },
  methods: {
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
