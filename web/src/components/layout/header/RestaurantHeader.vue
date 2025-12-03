<template>
  <header class="sticky top-0 z-50 bg-deep text-white shadow">
    <div class="mx-auto max-w-6xl px-4">
      <div class="flex h-16 items-center gap-3">
        <router-link to="/" class="items-center gap-3 flex-1" aria-label="Domov">
          <img src="@/assets/img/logo-scan-order.svg" alt="ScanOrder logo" class="w-40 select-none h-auto" />
        </router-link>

        <div class="flex flex-1 items-center justify-end">
          <RestaurantBadge label="Stôl 12" />
        </div>
      </div>
    </div>
  </header>
</template>

<script>
import RestaurantBadge from '../../restauracia/RestaurantBadge.vue';

export default {
  name: 'RestaurantHeader',
  components: { RestaurantBadge },
};
</script>

<style scoped></style>
