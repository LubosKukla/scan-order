<template>
  <div class="space-y-2 p-2">
    <label v-if="label" :for="name || inputId" class="text-sm font-bold text-deep flex items-center gap-1">
      {{ label }}
      <span v-if="required" class="text-danger text-xs font-semibold">*</span>
    </label>
    <div class="relative">
      <input
        :id="name || inputId"
        :type="inputType"
        :name="name"
        :placeholder="placeholder"
        :autocomplete="autocomplete"
        :required="required"
        :disabled="disabled"
        :value="modelValue"
        :aria-label="ariaLabel"
        :aria-invalid="hasError"
        v-bind="inputAttrs"
        :class="inputClasses"
        @input="onInput"
      />
      <button
        v-if="showsPasswordToggle"
        type="button"
        class="absolute inset-y-0 cursor-pointer right-2 flex items-center justify-center rounded-full p-1 text-deep/60 transition hover:text-deep focus:outline-none focus-visible:ring-1 focus-visible:ring-primary/10"
        @click="togglePasswordVisibility"
      >
        <font-awesome-icon :icon="passwordVisible ? faEyeSlash : faEye" class="h-5 w-5" />
      </button>
      <div v-if="showsSearchIcon" class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-deep/40">
        <font-awesome-icon :icon="faSearch" class="h-4 w-4" />
      </div>
    </div>
    <p v-if="hasError" class="text-xs font-semibold text-danger px-2">{{ error }}</p>
  </div>
</template>

<script>
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { faEye, faEyeSlash, faSearch } from '@fortawesome/free-solid-svg-icons';

let uid = 0;

export default {
  name: 'BaseInput',
  components: {
    FontAwesomeIcon,
  },
  inheritAttrs: false,
  props: {
    modelValue: {
      type: [String, Number],
      default: '',
    },
    type: {
      type: String,
      default: 'text',
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
      inputId: `base-input-${uid++}`,
      passwordVisible: false,
      faEye,
      faEyeSlash,
      faSearch,
    };
  },
  computed: {
    inputAttrs() {
      return this.$attrs;
    },
    ariaLabel() {
      return this.label || this.placeholder || this.name || 'Vstupné pole';
    },
    hasError() {
      return Boolean(this.error);
    },
    inputType() {
      if (this.type === 'password' && this.passwordVisible) {
        return 'text';
      }
      return this.type;
    },
    showsPasswordToggle() {
      return this.type === 'password';
    },
    showsSearchIcon() {
      return this.type === 'search';
    },
    inputClasses() {
      const base =
        'w-full rounded-xl border bg-ink py-2 text-sm font-semibold text-deep placeholder:text-deep/60 focus:outline-none transition disabled:cursor-not-allowed disabled:opacity-50';
      const stateClass = this.hasError
        ? 'border-danger focus:border-danger focus:bg-white focus:ring-2 focus:ring-danger/20'
        : 'border-surface/10 focus:border-primary focus:bg-white focus:ring-2 focus:ring-primary/10';
      const padding = [this.showsSearchIcon ? 'pl-12' : 'pl-4', this.showsPasswordToggle ? 'pr-14' : 'pr-4'];
      return [base, stateClass, ...padding].join(' ');
    },
  },
  methods: {
    onInput(event) {
      this.$emit('update:modelValue', event.target.value);
      this.$emit('input', event.target.value);
    },
    togglePasswordVisibility() {
      this.passwordVisible = !this.passwordVisible;
    },
  },
};
</script>
