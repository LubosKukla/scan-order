<template>
  <span :class="badgeClasses">
    <font-awesome-icon :icon="typeConfig.icon" class="h-3.5 w-3.5" />
    <span>{{ displayLabel }}</span>
  </span>
</template>

<script>
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { faStore, faDrumstickBite } from '@fortawesome/free-solid-svg-icons';

const TYPE_CONFIG = {
  restaurant: {
    label: 'Reštaurácia',
    classes: 'bg-primary text-white shadow-sm',
    icon: faStore,
  },
  menu_item: {
    label: 'Jedlo',
    classes: 'bg-deep/80 text-white shadow-sm',
    icon: faDrumstickBite,
  },
};

export default {
  name: 'ReviewTypeBadge',
  components: { FontAwesomeIcon },
  props: {
    type: {
      type: String,
      default: 'restaurant',
      validator: (value) => ['restaurant', 'menu_item'].includes(value),
    },
    label: {
      type: String,
      default: '',
    },
  },
  computed: {
    typeConfig() {
      return TYPE_CONFIG[this.type] || TYPE_CONFIG.restaurant;
    },
    badgeClasses() {
      return ['inline-flex items-center gap-2 rounded-lg px-2.5 py-1 text-xs font-semibold', this.typeConfig.classes];
    },
    displayLabel() {
      return this.label || this.typeConfig.label;
    },
  },
};
</script>
