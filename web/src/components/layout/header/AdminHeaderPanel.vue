<template>
  <header class="bg-white border-b border-surface/40 shadow-sm">
    <div class="flex flex-col gap-4 pl-6 pr-10 py-2 sm:flex-row sm:items-center sm:justify-between">
      <div class="flex flex-1 items-center gap-4 min-w-0">
        <h1 class="text-base font-semibold text-deep whitespace-nowrap">
          {{ currentTitle }}
        </h1>
        <div class="flex-1 max-w-lg hidden md:block">
          <BaseInput
            v-model="searchQuery"
            type="search"
            placeholder="Hľadať..."
            :label="null"
          />
        </div>
      </div>

      <div class="flex items-center justify-end gap-4">
        <div class="relative">
          <button
            type="button"
            @mouseenter="toggleRestaurantDropdown"
            class="flex items-center gap-2 rounded-full px-1 py-1 transition hover:bg-ink/40 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary/40"
          >
            <div
              class="flex h-7 w-7 items-center justify-center rounded-full bg-primary text-xs font-semibold text-white"
            >
              {{ restaurantInitials }}
            </div>
            <span class="text-sm font-bold text-deep hidden sm:inline">
              {{ restaurantName }}
            </span>
            <svg
              class="h-3.5 w-3.5 text-deep/70 hidden sm:block"
              viewBox="0 0 20 20"
              fill="currentColor"
              aria-hidden="true"
            >
              <path
                fill-rule="evenodd"
                d="M5.293 7.293a1 1 0 0 1 1.414 0L10 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414l-4 4a1 1 0 0 1-1.414 0l-4-4a1 1 0 0 1 0-1.414Z"
                clip-rule="evenodd"
              />
            </svg>
          </button>
          <div
            v-if="showRestaurantDropdown"
            @mouseleave="toggleRestaurantDropdown"
            class="absolute right-0 mt-2 w-56 rounded-xl border border-surface/40 bg-white py-2 shadow-lg z-20"
          >
            <router-link to="/" class="block px-4 py-2 text-sm text-deep transition hover:bg-ink/60">
              Vrátiť sa na web
            </router-link>
            <router-link :to="restaurantMenuLink" class="block px-4 py-2 text-sm text-deep transition hover:bg-ink/60">
              Zobraziť menu reštaurácie
            </router-link>
            <router-link to="/moje-restauracie" class="block px-4 py-2 text-sm text-deep transition hover:bg-ink/60">
              Moje reštaurácie
            </router-link>
            <hr />
            <button
              type="button"
              @click="logout"
              class="flex w-full px-4 py-2 text-left text-sm font-semibold text-danger transition hover:bg-ink/60"
            >
              Odhlásiť sa
            </button>
          </div>
        </div>
      </div>
    </div>
  </header>
</template>

<script>
import BaseInput from '../../global/inputs/BaseInput.vue';

export default {
  name: 'AdminHeaderPanel',
  components: { BaseInput },
  data() {
    return {
      searchQuery: '',
      restaurantName: 'Reštaurácia u Jozefa',
      notificationCount: 3,
      showRestaurantDropdown: false,
    };
  },
  computed: {
    currentTitle() {
      const routeName = this.$route.meta.title;
      if (!routeName) return 'Scan&Order';
      return routeName;
    },
    restaurantInitials() {
      return this.restaurantName
        .split(' ')
        .map((part) => part[0])
        .join('')
        .substring(0, 2)
        .toUpperCase();
    },
    restaurantMenuLink() {
      return '/restauracia/nazov/menu';
    },
  },
  methods: {
    toggleRestaurantDropdown() {
      this.showRestaurantDropdown = !this.showRestaurantDropdown;
    },
    logout() {
      this.$emit('logout');
    },
  },
};
</script>
