<template>
  <div class="flex items-center gap-2">
    <span v-if="showValue" class="text-md font-semibold text-deep">{{ formattedRating }}</span>
    <div class="flex items-center gap-1">
      <font-awesome-icon
        v-for="(state, index) in starStates"
        :key="index"
        :icon="stateIcon(state)"
        :class="starClass(state)"
        class="h-4 w-4"
      />
    </div>
  </div>
</template>

<script>
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { faStar as faStarSolid, faStarHalfAlt } from '@fortawesome/free-solid-svg-icons';
import { faStar as faStarRegular } from '@fortawesome/free-regular-svg-icons';
import { formatNumber } from '@/utils/formatters';

export default {
  name: 'ReviewRatingDisplay',
  components: { FontAwesomeIcon },
  props: {
    rating: {
      type: Number,
      required: true,
    },
    maxRating: {
      type: Number,
      default: 5,
    },
    showValue: {
      type: Boolean,
      default: false,
    },
  },
  computed: {
    formattedRating() {
      const rating = this.rating?.toFixed(1) || '0.0';
      return formatNumber(rating);
    },
    starStates() {
      const states = [];
      const rating = Math.max(0, Math.min(this.rating, this.maxRating));
      const fullStars = Math.floor(rating);
      const hasHalf = rating - fullStars >= 0.25 && rating - fullStars < 0.75;
      for (let i = 0; i < this.maxRating; i += 1) {
        if (i < fullStars) {
          states.push('full');
        } else if (i === fullStars && hasHalf) {
          states.push('half');
        } else {
          states.push('empty');
        }
      }
      return states;
    },
  },
  methods: {
    stateIcon(state) {
      if (state === 'half') return faStarHalfAlt;
      if (state === 'empty') return faStarRegular;
      return faStarSolid;
    },
    starClass(state) {
      if (state === 'empty') return 'text-ink';
      return 'text-amber-400';
    },
  },
};
</script>
