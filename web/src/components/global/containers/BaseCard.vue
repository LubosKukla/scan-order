<template>
  <section
    :class="[
      'rounded-2xl border border-ink bg-white shadow-[0px_3px_8px_rgba(0,0,0,0.08)]',
      noPadding ? '' : 'p-6',
    ]"
  >
    <slot />
  </section>
</template>

<script>
export default {
  name: 'BaseCard',
  props: {
    noPadding: {
      type: Boolean,
      default: false,
    },
  },
};
</script>
