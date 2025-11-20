<template>
  <span :class="badgeClasses">{{ statusLabel }}</span>
</template>

<script>
const STATUS_MAP = {
  new: {
    label: 'Nová',
    classes: 'bg-blue-100 text-blue-700',
  },
  preparing: {
    label: 'Pripravuje sa',
    classes: 'bg-amber-100 text-amber-700',
  },
  ready: {
    label: 'Pripravená',
    classes: 'bg-cyan-100 text-cyan-700',
  },
  completed: {
    label: 'Dokončená',
    classes: 'bg-emerald-100 text-emerald-700',
  },
  cancelled: {
    label: 'Zrušená',
    classes: 'bg-rose-100 text-rose-700',
  },
};

export default {
  name: 'OrderStatusBadge',
  props: {
    status: {
      type: String,
      default: 'new',
    },
    label: {
      type: String,
      default: '',
    },
  },
  computed: {
    config() {
      return STATUS_MAP[this.status] || STATUS_MAP.new;
    },
    statusLabel() {
      return this.label || this.config.label;
    },
    badgeClasses() {
      return ['px-3 py-1 rounded-lg text-xs font-semibold', this.config.classes];
    },
  },
};
</script>
