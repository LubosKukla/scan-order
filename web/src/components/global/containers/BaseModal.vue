<template>
  <Teleport to="body">
    <transition name="modal-fade">
      <div v-if="modelValue" class="modal-backdrop" @click.self="close">
        <div class="modal-card" role="dialog" aria-modal="true">
          <header class="modal-header">
            <slot name="title">
              <h2 class="text-lg font-semibold text-deep">{{ title }}</h2>
            </slot>
            <button v-if="showClose" class="close-btn" @click="close" aria-label="Zavrieť">×</button>
          </header>
          <div class="modal-body">
            <slot />
          </div>
          <footer v-if="$slots.footer" class="modal-footer">
            <slot name="footer" />
          </footer>
        </div>
      </div>
    </transition>
  </Teleport>
</template>

<script>
export default {
  name: 'BaseModal',
  props: {
    modelValue: {
      type: Boolean,
      default: false,
    },
    title: {
      type: String,
      default: '',
    },
    showClose: {
      type: Boolean,
      default: true,
    },
  },
  methods: {
    close() {
      this.$emit('update:modelValue', false);
      this.$emit('close');
    },
  },
};
</script>

<style scoped lang="scss">
.modal-backdrop {
  position: fixed;
  inset: 0;
  background: rgba(4, 8, 13, 0.65);
  backdrop-filter: blur(6px);
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 2rem;
  z-index: 1200;
}

.modal-card {
  width: min(640px, 100%);
  background: white;
  border: 2px solid rgba(15, 23, 42, 0.1);
  border-radius: 1rem;
  box-shadow: 0 40px 60px rgba(0, 0, 0, 0.25);
  display: flex;
  flex-direction: column;
  max-height: calc(100vh - 4rem);
}

.modal-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 1rem 1.5rem 1rem;
}

.close-btn {
  font-size: 1.5rem;
  line-height: 1;
  background: transparent;
  border: none;
  cursor: pointer;
  color: #0f172a;
  transition: transform 0.15s ease, color 0.15s ease;

  &:hover {
    color: #0ccaba;
    transform: scale(1.1);
  }
}

.modal-body {
  padding: 1rem 1.5rem 1rem;
  overflow-y: auto;
}

.modal-footer {
  padding: 1rem 1.5rem 1rem;
  border-top: 1px solid rgba(15, 23, 42, 0.08);
  display: flex;
  justify-content: flex-end;
  gap: 0.75rem;
}

.modal-fade-enter-active,
.modal-fade-leave-active {
  transition: opacity 0.2s ease;
}

.modal-fade-enter-from,
.modal-fade-leave-to {
  opacity: 0;
}
</style>
