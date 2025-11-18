<template>
  <label class="inline-flex items-start gap-3 text-sm text-deep select-none cursor-pointer">
    <input type="checkbox" :checked="modelValue" :disabled="disabled" class="peer sr-only" @change="onChange" />
    <span
      class="flex h-5 w-5 items-center justify-center rounded-md border border-surface/10 bg-ink transition peer-checked:border-primary peer-checked:bg-primary peer-disabled:opacity-50"
    ></span>
    <span>
      <slot />
    </span>
  </label>
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
  },
  methods: {
    onChange(event) {
      this.$emit('update:modelValue', event.target.checked);
      this.$emit('change', event.target.checked);
    },
  },
};
</script>
