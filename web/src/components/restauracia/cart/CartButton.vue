<template>
  <button
    type="button"
    :class="[
      'w-full rounded-full px-6 py-3 text-center text-base font-semibold transition shadow-md hover:shadow-lg focus:outline-none focus-visible:ring-2 focus-visible:ring-primary/30',
      variantClasses,
    ]"
    @click="$emit('click')"
  >
    <slot>{{ label }}</slot>
  </button>
</template>

<script>
export default {
  name: 'CartButton',
  emits: ['click'],
  props: {
    label: { type: String, default: '' },
    variant: { type: String, default: 'primary' },
  },
  computed: {
    variantClasses() {
      if (this.variant === 'dark') return 'bg-deep text-white';
      return 'bg-primary text-white';
    },
  },
};
</script>
