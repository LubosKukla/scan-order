<template>
  <BaseCard class="flex flex-col gap-3 text-deep">
    <div class="flex gap-5">
      <img :src="image" :alt="title" class="h-16 w-16 md:h-24 md:w-24 rounded-2xl object-cover" />
      <div class="flex flex-1 items-start justify-between gap-3">
        <div class="space-y-4 flex flex-col justify-between h-full">
          <p class="text-lg font-semibold leading-tight">{{ title }}</p>
          <p class="text-base font-semibold text-primary">{{ priceLabel }}</p>
        </div>
        <div class="flex flex-col items-end gap-2">
          <button
            type="button"
            class="w-fit rounded-xl bg-danger/10 p-2 text-danger transition hover:bg-danger/20"
            aria-label="Odstrániť položku"
            @click="$emit('remove')"
          >
            <FontAwesomeIcon :icon="faTrash" class="h-2 w-2 md:h-4 md:w-4" />
          </button>
          <div class="flex justify-end">
            <div class="inline-flex items-center md:gap-2 rounded-full bg-primary px-3 py-2 text-white shadow">
              <button
                type="button"
                class="grid h-5 w-5 md:h-7 md:w-7 place-items-center rounded-full bg-white/20"
                aria-label="Zmenšiť množstvo"
                @click="$emit('decrement')"
              >
                <FontAwesomeIcon :icon="faMinus" class="h-2 w-2 md:h-4 md:w-4" />
              </button>
              <span class="min-w-[2rem] text-center text-base font-bold">{{ quantity }}</span>
              <button
                type="button"
                class="grid h-5 w-5 md:h-7 md:w-7 place-items-center rounded-full bg-white/20"
                aria-label="Pridať množstvo"
                @click="$emit('increment')"
              >
                <FontAwesomeIcon :icon="faPlus" class="h-2 w-2 md:h-4 md:w-4" />
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </BaseCard>
</template>

<script>
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { faTrash, faMinus, faPlus } from '@fortawesome/free-solid-svg-icons';
import BaseCard from '@/components/global/containers/BaseCard.vue';
import { formatCurrency } from '@/utils/formatters';

export default {
  name: 'CartItem',
  components: { FontAwesomeIcon, BaseCard },
  emits: ['increment', 'decrement', 'remove'],
  props: {
    title: { type: String, required: true },
    price: { type: [Number, String], required: true },
    quantity: { type: Number, default: 1 },
    image: {
      type: String,
      default: 'https://images.unsplash.com/photo-1550547660-d9450f859349?auto=format&fit=crop&w=400&q=80',
    },
    currency: { type: String, default: 'EUR' },
  },
  data() {
    return {
      faTrash,
      faMinus,
      faPlus,
    };
  },
  computed: {
    priceLabel() {
      return formatCurrency(Number(this.price), 'sk-SK', this.currency || 'EUR');
    },
  },
};
</script>
