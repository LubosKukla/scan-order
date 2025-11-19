<template>
  <div class="space-y-1">
    <label class="inline-flex items-start gap-3 text-sm text-deep select-none cursor-pointer">
      <input type="checkbox" :checked="modelValue" :disabled="disabled" class="peer sr-only" @change="onChange" />
      <span
        class="flex h-5 w-5 items-center justify-center rounded-md border transition"
        :class="boxClasses"
        aria-hidden="true"
      ></span>
      <span>
        <slot />
      </span>
    </label>
    <p v-if="hasError" class="text-xs font-semibold text-danger px-2">{{ error }}</p>
  </div>
</template>

<script>
export default {
  name: 'BaseCheckbox',
  props: {
    modelValue: {
      type: Boolean,
      default: false,
    },
    disabled: {
      type: Boolean,
      default: false,
    },
    error: {
      type: String,
      default: '',
    },
  },
  computed: {
    hasError() {
      return Boolean(this.error);
    },
    boxClasses() {
      if (this.hasError) {
        return 'border-danger bg-white';
      }
      return this.modelValue ? 'border-primary bg-primary' : 'border-surface/10 bg-ink peer-disabled:opacity-50';
    },
  },
  methods: {
    onChange(event) {
      this.$emit('update:modelValue', event.target.checked);
      this.$emit('change', event.target.checked);
    },
  },
};
</script>
