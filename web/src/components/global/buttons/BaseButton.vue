<template>
  <button
    :type="type"
    :disabled="disabled"
    :class="[rootClasses, buttonClasses]"
    @click="$emit('click', $event)"
  >
    <span
      v-if="iconComponent"
      class="flex h-4 w-4 items-center justify-center transition-transform duration-200"
      :class="iconHoverClass"
    >
      <font-awesome-icon :icon="iconComponent" />
    </span>
    <span :class="labelHoverClass">
      <slot />
    </span>
  </button>
</template>

<script>
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import {
  faPlus,
  faPrint,
  faFileExport,
  faArrowRotateRight,
  faDownload,
  faPaperPlane,
  faWandMagicSparkles,
  faArrowRightToBracket,
} from '@fortawesome/free-solid-svg-icons';

const ICONS = {
  add: faPlus,
  print: faPrint,
  export: faFileExport,
  refresh: faArrowRotateRight,
  download: faDownload,
  send: faPaperPlane,
  generate: faWandMagicSparkles,
  login: faArrowRightToBracket,
};

export default {
  name: 'BaseButton',
  components: { FontAwesomeIcon },
  props: {
    type: {
      type: String,
      default: 'button',
    },
    variant: {
      type: String,
      default: 'primary',
      validator: (value) => ['primary', 'secondary'].includes(value),
    },
    icon: {
      type: String,
      default: '',
    },
    disabled: {
      type: Boolean,
      default: false,
    },
  },
  computed: {
    rootClasses() {
      return [
        'inline-flex items-center gap-2 rounded-xl px-6 py-2 text-sm font-semibold transition focus:outline-none focus-visible:ring-2 focus-visible:ring-primary/20 disabled:cursor-not-allowed disabled:opacity-60',
        { group: !this.disabled, 'cursor-pointer': !this.disabled },
      ];
    },
    buttonClasses() {
      if (this.variant === 'secondary') {
        return this.disabled
          ? 'bg-white text-deep border border-[0.15rem] border-deep/20'
          : 'bg-white text-deep border border-[0.15rem] border-deep/20 hover:bg-ink/40 hover:border-deep/40 hover:-translate-y-0.5';
      }
      return this.disabled
        ? 'bg-primary text-white'
        : 'bg-primary text-white hover:bg-primary/90 hover:-translate-y-0.5';
    },
    iconComponent() {
      if (!this.icon) return null;
      return ICONS[this.icon] || null;
    },
    iconHoverClass() {
      if (this.disabled || !this.icon) return '';
      return 'group-hover:-translate-y-0.5';
    },
    labelHoverClass() {
      if (this.disabled) return '';
      return 'group-hover:-translate-y-0.5';
    },
  },
};
</script>
