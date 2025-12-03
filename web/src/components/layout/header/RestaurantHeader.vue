<template>
  <header class="sticky top-0 z-50 bg-deep text-white shadow">
    <div class="mx-auto max-w-6xl px-4">
      <div class="flex h-16 items-center gap-3">
        <router-link to="/" class="items-center gap-3 flex-1" aria-label="Domov">
          <img src="@/assets/img/logo-scan-order.svg" alt="ScanOrder logo" class="w-40 select-none h-auto" />
        </router-link>

        <div class="flex flex-1 items-center justify-end gap-2">
          <RestaurantBadge label="Stol 12" />
          <RestaurantLogin />
        </div>
      </div>
    </div>
  </header>
</template>

<script>
import RestaurantBadge from '../../restauracia/RestaurantBadge.vue';
import RestaurantLogin from '../../restauracia/RestaurantLogin.vue';

export default {
  name: 'RestaurantHeader',
  components: { RestaurantBadge, RestaurantLogin },
};
</script>

<style scoped></style>
