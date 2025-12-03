<template>
  <section class="bg-deep/95 text-white">
    <div class="mx-auto max-w-5xl px-4 py-10 space-y-2">
      <p class="text-sm sm:text-base md:text-lg text-white/90 px-2 mb-0">Vitajte v našej reštaurácii</p>
      <h1 class="text-lg sm:text-2xl md:text-3xl font-bold leading-tight px-2">Objednajte si jedlo priamo zo stola</h1>

      <BaseInput type="search" placeholder="Vyhladať jedlo ..." />

      <div class="flex flex-wrap gap-2 md:gap-4">
        <RestaurantCategory label="Všetko" />
        <RestaurantCategory label="Pizza" />
        <RestaurantCategory label="Hamburger" />
        <RestaurantCategory label="Dezerty" />
      </div>
    </div>
  </section>
</template>

<script>
import BaseInput from '../global/inputs/BaseInput.vue';
import RestaurantCategory from '../layout/funkcionality/RestaurantCategory.vue';
export default {
  components: {
    BaseInput,
    RestaurantCategory,
  },
};
</script>
