<template>
  <span :class="badgeClasses">
    <font-awesome-icon :icon="typeConfig.icon" class="h-3.5 w-3.5" />
    <span>{{ typeLabel }}</span>
  </span>
</template>

<script>
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { faUtensils, faBagShopping, faMotorcycle } from '@fortawesome/free-solid-svg-icons';

const TYPE_CONFIG = {
  dineIn: {
    label: 'Na mieste',
    classes: 'bg-primary text-white',
    icon: faUtensils,
  },
  takeout: {
    label: 'Na odber',
    classes: 'bg-deep text-white',
    icon: faBagShopping,
  },
  delivery: {
    label: 'Donáška',
    classes: 'bg-black/80 text-white',
    icon: faMotorcycle,
  },
};

export default {
  name: 'OrderTypeBadge',
  components: { FontAwesomeIcon },
  props: {
    type: {
      type: String,
      default: 'dineIn',
    },
    label: {
      type: String,
      default: '',
    },
  },
  computed: {
    typeConfig() {
      return TYPE_CONFIG[this.type] || TYPE_CONFIG.dineIn;
    },
    typeLabel() {
      return this.label || this.typeConfig.label;
    },
    badgeClasses() {
      return ['inline-flex items-center gap-2 rounded-lg px-3 py-1 text-xs font-semibold', this.typeConfig.classes];
    },
  },
};
</script>
