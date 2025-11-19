<template>
  <section class="flex flex-col gap-2 rbg-ink/10 text-deep sm:flex-row sm:items-center sm:justify-between sm:gap-4">
    <div class="space-y-1">
      <h1 class="text-2xl font-semibold">{{ title }}</h1>
      <p v-if="subtitle" class="text-sm text-deep/70">{{ subtitle }}</p>
    </div>
    <div class="flex flex-wrap gap-3">
      <slot name="actions">
        <BaseButton v-if="actionLabel" :icon="actionIcon" @click="$emit('action-click')">
          {{ actionLabel }}
        </BaseButton>
      </slot>
    </div>
  </section>
</template>

<script>
import BaseButton from '@/components/global/buttons/BaseButton.vue';

export default {
  name: 'AdminPageHeader',
  components: { BaseButton },
  props: {
    title: { type: String, required: true },
    subtitle: { type: String, default: '' },
    actionLabel: { type: String, default: '' },
    actionIcon: { type: String, default: 'add' },
  },
  emits: ['action-click'],
};
</script>
