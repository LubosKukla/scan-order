<template>
  <button :type="type" :disabled="disabled" :class="[rootClasses, buttonClasses]" @click="$emit('click', $event)">
    <span v-if="iconComponent" class="flex h-4 w-4 items-center justify-center" :class="iconClasses">
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
  faPenToSquare,
  faTrash,
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
  edit: faPenToSquare,
  trash: faTrash,
};

export default {
  name: 'BaseButton',
  components: { FontAwesomeIcon },
  emits: ['click'],
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
      const base =
        'inline-flex items-center rounded-xl text-sm font-semibold transition focus:outline-none focus-visible:ring-2 focus-visible:ring-primary/20 cursor-pointer disabled:cursor-not-allowed disabled:opacity-60 cursor-pointer';
      const gap = this.iconComponent && this.hasSlotContent ? 'gap-2' : '';
      const padding = this.isCompact ? 'p-2' : 'px-6 py-2';

      return [[base, padding, gap].filter(Boolean).join(' '), { group: !this.disabled }];
    },
    buttonClasses() {
      if (this.variant === 'secondary') {
        return this.disabled
          ? 'bg-white text-deep border border-[0.1rem] border-deep/20'
          : 'bg-white text-deep border border-[0.1rem] border-deep/20 hover:bg-ink/40 hover:border-deep/40 hover:-translate-y-0.5';
      }
      return this.disabled
        ? 'bg-primary text-white'
        : 'bg-primary text-white hover:bg-primary/90 hover:-translate-y-0.5';
    },
    iconComponent() {
      if (!this.icon) return null;
      return ICONS[this.icon] || null;
    },
    slotNodes() {
      if (!this.$slots.default) return [];
      return this.$slots.default().filter((node) => {
        if (typeof node.children === 'string') {
          return node.children.trim().length > 0;
        }
        return true;
      });
    },
    hasSlotContent() {
      return this.slotNodes.length > 0;
    },
    isSingleNonTextSlot() {
      if (this.slotNodes.length !== 1) return false;
      const node = this.slotNodes[0];
      return typeof node.children !== 'string';
    },
    isCompact() {
      if (this.iconComponent && !this.hasSlotContent) return true;
      if (!this.iconComponent && this.isSingleNonTextSlot) return true;
      return false;
    },
    iconClasses() {
      if (!this.icon) return '';

      const classes = [];

      if (!this.disabled) {
        classes.push('transition-transform duration-200');
        classes.push('group-hover:-translate-y-0.5');
      }

      if (this.icon === 'trash') {
        classes.push('text-danger');
      }

      return classes.join(' ');
    },

    labelHoverClass() {
      if (this.disabled) return '';
      return 'group-hover:-translate-y-0.5';
    },
  },
};
</script>
