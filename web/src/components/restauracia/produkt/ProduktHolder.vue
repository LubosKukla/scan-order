<template>
  <section class="w-full space-y-4">
    <div class="flex items-center justify-between px-1">
      <h2 class="text-xl font-bold text-deep">{{ title }}</h2>
      <span class="text-sm font-semibold text-deep">{{ items.length }} {{ itemsText(items.length) }}</span>
    </div>

    <div class="flex flex-wrap justify-center gap-8 xl:gap-16">
      <RestauraciaItem
        v-for="item in items"
        :key="item.id"
        class="w-full max-w-sm"
        :title="item.name"
        :description="item.desc"
        :price="item.price"
        :tags="item.allergens"
        :likes="item.likes"
        :favorite="item.isFav"
        @toggle-favorite="emitToggle(item.id)"
        @add-to-cart="emitAdd(item.id)"
      />
    </div>
  </section>
</template>

<script>
import RestauraciaItem from './RestauraciaItem.vue';

export default {
  name: 'ProduktHolder',
  components: { RestauraciaItem },
  emits: ['toggle-favorite', 'add-to-cart'],
  props: {
    title: { type: String, required: true },
    items: { type: Array, default: () => [] },
  },

  methods: {
    emitToggle(id) {
      this.$emit('toggle-favorite', id);
    },
    emitAdd(id) {
      this.$emit('add-to-cart', id);
    },
    itemsText(length) {
      if (length === 1) return 'Položka';
      if ([2, 3, 4].includes(length)) return 'Položky';
      return 'Položiek';
    },
  },
};
</script>
