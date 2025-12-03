<template>
  <div class="box overflow-hidden rounded-xl bg-white text-deep shadow-lg ring-1 ring-ink/10 max-w-xs">
    <div class="relative aspect-4/3 overflow-hidden">
      <img :src="image" :alt="title" class="h-full w-full object-cover" />
      <button
        type="button"
        class="absolute right-3 top-3 inline-flex h-10 w-10 items-center justify-center rounded-full bg-white shadow-md ring-1 ring-ink/10 transition hover:-translate-y-0.5"
        @click="$emit('toggle-favorite')"
        :aria-pressed="favorite"
      >
        <FontAwesomeIcon
          :icon="favorite ? faHeartSolid : faHeartRegular"
          :class="favorite ? 'text-danger' : 'text-deep/50'"
        />
      </button>
    </div>

    <div class="space-y-3 px-4 pb-4 pt-3">
      <div>
        <h3 class="text-lg font-semibold leading-tight">{{ title }}</h3>
        <p class="mt-1 text-sm leading-snug text-deep/70">{{ description }}</p>
      </div>

      <div v-if="tags && tags.length" class="flex flex-wrap gap-2">
        <span
          v-for="tag in tags"
          :key="tag"
          class="inline-flex items-center rounded-full bg-primary/10 px-3 py-1 text-xs font-semibold text-primary"
        >
          {{ tag }}
        </span>
      </div>

      <div class="flex items-center justify-between pt-1">
        <div class="space-y-0.5">
          <p class="text-xl font-bold text-deep">{{ priceLabel }}</p>
          <p v-if="likes !== null" class="text-xs text-deep/60">{{ likes }} srdiečok</p>
        </div>
        <button
          type="button"
          class="inline-flex h-12 w-12 items-center justify-center rounded-full bg-primary text-white shadow-lg transition hover:scale-105"
          @click="$emit('add-to-cart')"
          aria-label="Pridať do košíka"
        >
          <FontAwesomeIcon :icon="faPlus" class="h-5 w-5" />
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { faHeart as faHeartSolid, faPlus } from '@fortawesome/free-solid-svg-icons';
import { faHeart as faHeartRegular } from '@fortawesome/free-regular-svg-icons';
import { formatCurrency } from '@/utils/formatters';

export default {
  name: 'RestauraciaItem',
  components: { FontAwesomeIcon },
  emits: ['toggle-favorite', 'add-to-cart'],
  props: {
    title: { type: String, required: true },
    description: { type: String, default: '' },
    price: { type: [Number, String], required: true },
    image: {
      type: String,
      default: 'https://images.unsplash.com/photo-1540189549336-e6e99c3679fe?auto=format&fit=crop&w=800&q=80',
    },
    tags: {
      type: Array,
      default: () => [],
    },
    likes: {
      type: [Number, String, null],
      default: null,
    },
    favorite: {
      type: Boolean,
      default: false,
    },
    currency: {
      type: String,
      default: 'EUR',
    },
  },
  data() {
    return {
      faHeartSolid,
      faHeartRegular,
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

<style lang="scss" scoped>
.box {
  max-width: 18em;
}

@media only screen and (max-width: 1280px) {
  .box {
    max-width: 14em;
  }
}

@media only screen and (max-width: 1024px) {
  .box {
    max-width: 12em;
  }
}

@media only screen and (max-width: 480px) {
  .box {
    max-width: 100%;
  }
}
</style>
