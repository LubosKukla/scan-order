<template>
  <span :class="tagClasses">
    <font-awesome-icon :icon="resolvedIcon" class="h-3.5 w-3.5" />
    <span class="font-bold">{{ label }}</span>
  </span>
</template>

<script>
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { faQrcode, faGlobe, faMobileScreenButton } from '@fortawesome/free-solid-svg-icons';

const SOURCE_CONFIG = {
  qr: {
    icon: faQrcode,
    classes: 'bg-ink/50 text-deep',
  },
  online: {
    icon: faGlobe,
    classes: 'bg-primary/10 text-primary',
  },
  kiosk: {
    icon: faMobileScreenButton,
    classes: 'bg-surface/20 text-deep',
  },
};

export default {
  name: 'ReviewSourceTag',
  components: { FontAwesomeIcon },
  props: {
    type: {
      type: String,
      default: 'qr',
    },
    label: {
      type: String,
      required: true,
    },
  },
  computed: {
    config() {
      return SOURCE_CONFIG[this.type] || SOURCE_CONFIG.qr;
    },
    tagClasses() {
      return [
        'inline-flex items-center gap-2 rounded-lg border border-deep/10 bg-white px-3 py-1 text-xs font-semibold',
        this.config.classes,
      ];
    },
    resolvedIcon() {
      return this.config.icon;
    },
  },
};
</script>
