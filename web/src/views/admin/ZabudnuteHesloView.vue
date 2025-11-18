<template>
  <section class="min-h-screen flex items-center justify-center py-12 px-4">
    <BaseCard class="w-full max-w-xl">
      <div class="flex flex-col items-center gap-2 text-center">
        <img src="@/assets/img/scan-order-logo-black.png" alt="EasyOrder" class="h-full max-w-2xs w-[90%] px-8" />
        <p class="mt-4 text-2xl font-semibold text-deep">Zabudnuté heslo</p>
        <p class="text-sm text-deep/70">Zadajte svoj email a pošleme vám inštrukcie</p>
      </div>

      <form class="mt-8 space-y-6" @submit.prevent="submit">
        <div class="space-y-2 text-left">
          <BaseInput id="email" v-model="email" type="email" placeholder="vas.email@priklad.sk" label="Email" />
        </div>
        <BaseButton icon="send" class="w-full justify-center">Odoslať inštrukcie</BaseButton>
        <p class="text-center text-sm text-deep">
          Spomenuli ste si?
          <router-link to="/admin/login" class="font-semibold text-primary hover:text-primary/80">
            Naspäť na prihlásenie
          </router-link>
        </p>
      </form>
    </BaseCard>
  </section>
</template>

<script>
import BaseCard from '../../components/global/containers/BaseCard.vue';
import BaseInput from '../../components/global/inputs/BaseInput.vue';
import BaseButton from '../../components/global/buttons/BaseButton.vue';

export default {
  name: 'AdminZabudnuteHesloView',
  components: { BaseCard, BaseInput, BaseButton },
  data() {
    return {
      email: '',
    };
  },
  methods: {
    submit() {
      this.$emit('forgot-password', this.email);
    },
  },
};
</script>
