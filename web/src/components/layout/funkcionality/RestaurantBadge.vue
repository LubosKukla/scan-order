<template>
  <div
    class="inline-flex items-center gap-2 rounded-lg bg-primary/5 px-4 py-2 text-white shadow-sm ring-1 ring-primary/10 backdrop-blur-sm"
  >
    <FontAwesomeIcon :icon="faQrcode" class="h-4 w-4 text-primary" />
    <span class="text-sm font-semibold">{{ label }}</span>
  </div>
</template>

<script>
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { faQrcode } from '@fortawesome/free-solid-svg-icons';

export default {
  name: 'RestaurantBadge',
  components: { FontAwesomeIcon },
  props: {
    label: {
      type: String,
      required: true,
    },
  },
  data() {
    return { faQrcode };
  },
};
</script>
