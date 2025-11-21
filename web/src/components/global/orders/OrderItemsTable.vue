<template>
  <div>
    <p v-if="title" class="text-sm font-semibold text-deep mb-2">{{ title }}</p>
    <BaseCard class="bg-ink space-y-2 overflow-x-auto">
      <table class="w-full text-sm text-deep">
        <thead>
          <tr class="text-left text-xs font-semibold text-deep/60 border-b border-ink/40">
            <th class="py-2">Položka</th>
            <th class="py-2 text-center">Množstvo</th>
            <th class="py-2 text-right">Cena</th>
            <th v-if="removable" class="py-2 text-right">Akcie</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(item, index) in items" :key="item.id || `${item.name}-${item.variant}`" class="border-b border-ink/40">
            <td class="py-3">
              <p class="font-semibold">{{ item.name }}</p>
              <p v-if="item.variant" class="text-xs text-deep/60">Varianta: {{ item.variant }}</p>
              <p v-if="item.extras?.length" class="text-xs text-deep/60">Pridané: {{ item.extras.join(', ') }}</p>
              <p v-if="item.removed?.length" class="text-xs text-deep/60">Bez: {{ item.removed.join(', ') }}</p>
            </td>
            <td class="py-3 text-center">{{ item.quantity }}x</td>
            <td class="py-3 text-right">{{ formatAmount(item.price * item.quantity) }}</td>
            <td v-if="removable" class="py-3 text-right">
              <BaseButton variant="noBackground" icon="close" class="text-danger hover:text-danger" @click="$emit('remove', index)" />
            </td>
          </tr>
        </tbody>
        <tfoot v-if="showTotal">
          <tr class="text-sm font-bold">
            <td class="py-3">Celková suma</td>
            <td></td>
            <td class="py-3 text-right" :colspan="removable ? 2 : 1">{{ formatAmount(total) }}</td>
          </tr>
        </tfoot>
      </table>
    </BaseCard>
  </div>
</template>

<script>
import BaseCard from '@/components/global/containers/BaseCard.vue';
import BaseButton from '@/components/global/buttons/BaseButton.vue';
import { formatCurrency as formatCurrencyValue } from '@/utils/formatters';

export default {
  name: 'OrderItemsTable',
  components: { BaseCard, BaseButton },
  props: {
    items: {
      type: Array,
      default: () => [],
    },
    total: {
      type: Number,
      default: 0,
    },
    title: {
      type: String,
      default: '',
    },
    showTotal: {
      type: Boolean,
      default: true,
    },
    removable: {
      type: Boolean,
      default: false,
    },
  },
  methods: {
    formatAmount(value) {
      return formatCurrencyValue(value);
    },
  },
};
</script>
