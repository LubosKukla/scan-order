<template>
  <div
    class="sticky bottom-0 left-0 right-0 border-t border-ink/10 bg-white/95 shadow-[0_-6px_20px_rgba(0,0,0,0.12)] backdrop-blur"
  >
    <div class="mx-auto max-w-xl px-4 py-3">
      <router-link
        class="block overflow-hidden rounded-xl bg-primary px-4 py-3 text-deep shadow-md transition hover:shadow-lg"
        :to="link"
      >
        <div class="flex items-center justify-between gap-4">
          <div class="flex items-center gap-3">
            <div class="relative flex h-10 w-10 items-center justify-center">
              <FontAwesomeIcon :icon="faCartShopping" class="h-6 w-6 text-white" />
              <span
                v-if="count > 0"
                class="absolute -right-1 -top-1 inline-flex min-h-[1.4rem] min-w-[1.4rem] items-center justify-center rounded-full bg-white px-1 text-xs font-bold text-deep shadow"
              >
                {{ count }}
              </span>
            </div>
            <div class="leading-tight">
              <p class="text-base font-semibold text-white">Košík ({{ count }})</p>
              <p class="text-sm text-white">{{ tableLabel }}</p>
            </div>
          </div>

          <div class="flex items-center gap-2 text-lg font-bold text-white">
            <span>{{ totalLabel }}</span>
            <FontAwesomeIcon :icon="faArrowRight" class="h-4 w-4" aria-hidden="true" />
          </div>
        </div>
      </router-link>
    </div>
  </div>
</template>

<script>
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { faCartShopping, faArrowRight } from '@fortawesome/free-solid-svg-icons';
import { formatCurrency } from '@/utils/formatters';

export default {
  name: 'CartBar',
  components: { FontAwesomeIcon },
  props: {
    count: { type: Number, default: 0 },
    total: { type: [Number, String], default: 0 },
    tableLabel: { type: String, default: 'Stôl 12' },
    link: { type: [String, Object], default: '/restauracia/kosik' },
    currency: { type: String, default: 'EUR' },
  },
  data() {
    return { faCartShopping, faArrowRight };
  },
  computed: {
    totalLabel() {
      return formatCurrency(Number(this.total), 'sk-SK', this.currency || 'EUR');
    },
  },
};
</script>
