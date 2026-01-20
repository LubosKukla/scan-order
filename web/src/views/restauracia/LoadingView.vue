<template>
  <section class="relative min-h-screen overflow-hidden text-white">
    <div class="absolute inset-0 bg-primary bg-gradient-to-br from-primary/95 via-primary/85 to-primary/70"></div>
    <div class="absolute -left-20 -top-24 h-64 w-64 rounded-full bg-white/20 blur-3xl"></div>
    <div class="absolute -bottom-24 -right-24 h-72 w-72 rounded-full bg-white/15 blur-3xl"></div>

    <div class="relative z-10 flex min-h-screen flex-col items-center justify-center px-6 text-center">
      <div class="relative mb-10 flex items-center justify-center">
        <div class="qr-card">
          <img src="@/assets/img/only-logo.svg" alt="Scan&Order QR" class="h-20 w-20" />
        </div>
      </div>

      <div class="space-y-3">
        <p class="text-4xl font-extrabold tracking-[0.24em]">SKENUJ</p>
        <p class="text-2xl font-light tracking-[0.35em]">A</p>
        <p class="text-4xl font-extrabold tracking-[0.2em]">OBJEDNAJ</p>
        <p class="text-sm text-white/85">Rýchle a jednoduché objednávanie</p>
      </div>

      <div class="mt-12 w-full max-w-xs">
        <div class="flex items-center gap-3 rounded-2xl bg-white/15 px-4 py-3 backdrop-blur">
          <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/20">
            <font-awesome-icon :icon="faQrcode" class="h-5 w-5 text-white" />
          </div>
          <div class="text-left">
            <p class="text-sm font-semibold">Scan&Order</p>
            <p class="text-xs text-white/75">Všetky práva vyhradené</p>
          </div>
        </div>

        <div class="mt-4 h-1.5 w-full overflow-hidden rounded-full bg-white/30">
          <div class="loading-bar h-full w-full rounded-full bg-white/90"></div>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { faQrcode } from '@fortawesome/free-solid-svg-icons';

export default {
  name: 'RestauraciaLoadingView',
  components: { FontAwesomeIcon },
  data() {
    return {
      faQrcode,
      timeoutId: null,
    };
  },
  mounted() {
    this.timeoutId = setTimeout(() => {
      const rawNext = this.$route.query.next;
      const nextPath = typeof rawNext === 'string' && rawNext ? rawNext : '/restauracia/menu';
      this.$router.replace(nextPath);
    }, 5000);
  },
  beforeUnmount() {
    if (this.timeoutId) {
      clearTimeout(this.timeoutId);
    }
  },
};
</script>

<style scoped>
.qr-card {
  display: flex;
  height: 110px;
  width: 110px;
  align-items: center;
  justify-content: center;
  border-radius: 28px;
  background: rgba(255, 255, 255, 0.18);
  box-shadow: 0 20px 40px rgba(0, 0, 0, 0.18);
  backdrop-filter: blur(8px);
}

.loading-line {
  position: absolute;
  height: 2px;
  width: 320px;
  background: rgba(255, 255, 255, 0.35);
}

.loading-bar {
  transform: translateX(-100%);
  animation: loading-bar 5s linear forwards;
}

@keyframes loading-bar {
  to {
    transform: translateX(0);
  }
}

@keyframes dot-pulse {
  0%,
  100% {
    transform: scale(0.7);
    opacity: 0.5;
  }
  50% {
    transform: scale(1);
    opacity: 1;
  }
}

@media (max-width: 640px) {
  .loading-line {
    width: 220px;
  }
}
</style>
