<template>
  <span :class="badgeClasses">{{ displayLabel }}</span>
</template>

<script>
const STATUS_MAP = {
  paid: { label: 'Paid', classes: 'bg-primary/10 text-primary/80' },
  processing: { label: 'Processing', classes: 'bg-ink text-deep/80' },
  pending: { label: 'Pending', classes: 'bg-deep/10 text-deep/80' },
  unpaid: { label: 'Unpaid', classes: 'bg-danger/10 text-danger/80' },
  failed: { label: 'Failed', classes: 'bg-danger/10 text-danger/80' },
  refunded: { label: 'Refunded', classes: 'bg-deep/10 text-deep/80' },
};

export default {
  name: 'PaymentStatusBadge',
  props: {
    status: {
      type: String,
      default: '',
    },
    label: {
      type: String,
      default: '',
    },
  },
  computed: {
    normalizedStatus() {
      return (this.status || '').toString().trim().toLowerCase();
    },
    config() {
      return (
        STATUS_MAP[this.normalizedStatus] || {
          label: this.status || 'Unknown',
          classes: 'bg-deep/10 text-deep',
        }
      );
    },
    displayLabel() {
      return this.label || this.config.label;
    },
    badgeClasses() {
      return ['inline-flex items-center rounded-md px-3 py-1 text-xs font-semibold', this.config.classes];
    },
  },
};
</script>
