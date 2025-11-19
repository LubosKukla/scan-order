<template>
  <div class="space-y-2 p-2">
    <label v-if="label" :for="name || textareaId" class="text-sm font-bold text-deep flex items-center gap-1">
      {{ label }}
      <span v-if="required" class="text-danger text-xs font-semibold">*</span>
    </label>
    <div class="relative">
      <textarea
        :id="name || textareaId"
        :name="name"
        :placeholder="placeholder"
        :rows="rows"
        :maxlength="maxlength"
        :required="required"
        :autocomplete="autocomplete"
        :disabled="disabled"
        :value="modelValue"
        :aria-label="ariaLabel"
        :aria-invalid="hasError"
        v-bind="textareaAttrs"
        :class="textareaClasses"
        @input="onInput"
      ></textarea>
    </div>
    <p v-if="hasError" class="text-xs font-semibold text-danger px-2">{{ error }}</p>
  </div>
</template>

<script>
let textareaUid = 0;

export default {
  name: 'BaseTextarea',
  inheritAttrs: false,
  props: {
    modelValue: {
      type: [String, Number],
      default: '',
    },
    name: {
      type: String,
      default: '',
    },
    placeholder: {
      type: String,
      default: '',
    },
    label: {
      type: String,
      default: '',
    },
    rows: {
      type: [String, Number],
      default: 4,
    },
    maxlength: {
      type: [String, Number],
      default: null,
    },
    disabled: {
      type: Boolean,
      default: false,
    },
    autocomplete: {
      type: String,
      default: 'off',
    },
    required: {
      type: Boolean,
      default: false,
    },
    error: {
      type: String,
      default: '',
    },
  },
  data() {
    return {
      textareaId: `base-textarea-${textareaUid++}`,
    };
  },
  computed: {
    textareaAttrs() {
      return this.$attrs;
    },
    ariaLabel() {
      return this.label || this.placeholder || this.name || 'Textové pole';
    },
    hasError() {
      return Boolean(this.error);
    },
    textareaClasses() {
      const base =
        'w-full rounded-xl border bg-ink px-4 py-2 text-sm font-semibold text-deep placeholder:text-deep/60 focus:outline-none transition disabled:cursor-not-allowed disabled:opacity-50 resize-none';
      const stateClass = this.hasError
        ? 'border-danger focus:border-danger focus:bg-white focus:ring-2 focus:ring-danger/20'
        : 'border-surface/10 focus:border-primary focus:bg-white focus:ring-2 focus:ring-primary/10';
      return [base, stateClass].join(' ');
    },
  },
  methods: {
    onInput(event) {
      this.$emit('update:modelValue', event.target.value);
      this.$emit('input', event.target.value);
    },
  },
};
</script>
