<template>
  <BaseCard class="space-y-3 text-deep">
    <div class="flex items-center justify-between text-sm font-semibold">
      <span>Položky ({{ count }})</span>
      <span>{{ subtotalLabel }}</span>
    </div>
    <hr class="border-ink/10" />
    <div class="flex items-center justify-between text-base font-bold">
      <span>Spolu s DPH:</span>
      <span class="text-primary">{{ totalLabel }}</span>
    </div>
    <slot />
  </BaseCard>
</template>

<script>
import BaseCard from '@/components/global/containers/BaseCard.vue';
import { formatCurrency } from '@/utils/formatters';

export default {
  name: 'CartSummary',
  components: { BaseCard },
  props: {
    count: { type: Number, default: 0 },
    subtotal: { type: [Number, String], default: 0 },
    total: { type: [Number, String], default: 0 },
    currency: { type: String, default: 'EUR' },
  },
  computed: {
    subtotalLabel() {
      return formatCurrency(Number(this.subtotal), 'sk-SK', this.currency || 'EUR');
    },
    totalLabel() {
      return formatCurrency(Number(this.total), 'sk-SK', this.currency || 'EUR');
    },
  },
};
</script>
