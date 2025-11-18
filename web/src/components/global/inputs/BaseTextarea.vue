<template>
  <div class="space-y-2 p-2">
    <label v-if="label" :for="name || textareaId" class="text-sm font-bold text-deep">
      {{ label }}
    </label>
    <div class="relative">
      <textarea
        :id="name || textareaId"
        :name="name"
        :placeholder="placeholder"
        :rows="rows"
        :maxlength="maxlength"
        :autocomplete="autocomplete"
        :disabled="disabled"
        :value="modelValue"
        :aria-label="ariaLabel"
        v-bind="textareaAttrs"
        class="w-full rounded-xl border border-surface/10 bg-ink px-4 py-2 text-sm font-semibold text-deep placeholder:text-deep/60 focus:border-primary focus:bg-white focus:ring-2 focus:ring-primary/10 focus:outline-none transition disabled:cursor-not-allowed disabled:opacity-50 resize-none"
        @input="onInput"
      ></textarea>
    </div>
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
  },
  methods: {
    onInput(event) {
      this.$emit('update:modelValue', event.target.value);
      this.$emit('input', event.target.value);
    },
  },
};
</script>
