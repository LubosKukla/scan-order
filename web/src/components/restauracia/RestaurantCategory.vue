<template>
  <div
    class="inline-flex items-center gap-2 rounded-2xl bg-primary/5 px-4 py-2 text-white shadow-sm ring-1 ring-primary/10 backdrop-blur-sm"
  >
    <span class="text-sm font-semibold">{{ label }}</span>
  </div>
</template>

<script>
export default {
  name: 'RestaurantBadge',
  props: {
    label: {
      type: String,
      required: true,
    },
  },
};
</script>
