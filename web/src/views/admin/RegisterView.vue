<template>
  <section class="min-h-screen flex items-center justify-center py-12 px-4">
    <BaseCard class="w-full max-w-3xl space-y-8">
      <div class="flex flex-col items-center">
        <img src="@/assets/img/scan-order-logo-black.png" alt="EasyOrder" class="h-full max-w-2xs w-[90%] px-8" />
      </div>

      <div class="flex w-full items-center gap-4 text-sm font-semibold text-deep/50">
        <span class="flex h-8 w-8 items-center justify-center rounded-full bg-primary text-white">1</span>
        <div class="h-px flex-1 bg-deep/15" />
        <span class="flex h-8 w-8 items-center justify-center rounded-full bg-deep/15 text-deep">2</span>
        <div class="h-px flex-1 bg-deep/15" />
        <span class="flex h-8 w-8 items-center justify-center rounded-full bg-deep/15 text-deep">3</span>
      </div>

      <div class="text-center space-y-1">
        <p class="text-2xl font-semibold text-deep">Základná registrácia</p>
        <p class="text-sm text-deep/70">Vytvorte si účet pre vašu reštauráciu</p>
      </div>

      <form class="space-y-1" @submit.prevent="submit">
        <BaseInput
          id="restaurant-name"
          v-model="form.restaurantName"
          label="Názov reštaurácie"
          placeholder="Zadajte názov reštaurácie"
        />
        <BaseInput
          id="owner"
          v-model="form.ownerName"
          label="Meno majiteľa / zodpovednej osoby"
          placeholder="Zadajte celé meno"
        />
        <BaseInput id="email" v-model="form.email" type="email" label="Email" placeholder="vas.email@priklad.sk" />
        <BaseInput id="phone" v-model="form.phone" type="tel" label="Telefónne číslo" placeholder="+421 900 000 000" />
        <BaseInput
          id="password"
          v-model="form.password"
          type="password"
          label="Heslo"
          placeholder="Vytvorte silné heslo"
        />
        <BaseInput
          id="password-confirm"
          v-model="form.passwordConfirm"
          type="password"
          label="Potvrďte heslo"
          placeholder="Zopakujte heslo"
        />

        <BaseCheckbox v-model="form.acceptedTerms" class="my-4 mx-2">
          Súhlasím s
          <a href="#" class="font-semibold text-primary hover:text-primary/80 hover:underline">
            Obchodnými podmienkami
          </a>
          a
          <a href="#" class="font-semibold text-primary hover:text-primary/80 hover:underline">GDPR</a>
        </BaseCheckbox>

        <BaseButton type="submit" class="w-full justify-center mt-3">Pokračovať</BaseButton>
      </form>

      <div class="flex items-center gap-4">
        <div class="flex-1 h-px bg-deep/15"></div>
        <span class="text-deep font-xs text-base">alebo</span>
        <div class="flex-1 h-px bg-deep/15"></div>
      </div>

      <p class="text-center text-sm text-deep">
        Už máte účet?
        <router-link to="/admin/login" class="font-semibold text-primary hover:text-primary/80">
          Prihlásiť sa
        </router-link>
      </p>
    </BaseCard>
  </section>
</template>

<script>
import BaseCard from '../../components/global/containers/BaseCard.vue';
import BaseInput from '../../components/global/inputs/BaseInput.vue';
import BaseButton from '../../components/global/buttons/BaseButton.vue';
import BaseCheckbox from '../../components/global/inputs/BaseCheckbox.vue';

export default {
  name: 'AdminRegisterView',
  components: { BaseCard, BaseInput, BaseButton, BaseCheckbox },
  data() {
    return {
      form: {
        restaurantName: '',
        ownerName: '',
        email: '',
        phone: '',
        password: '',
        passwordConfirm: '',
        acceptedTerms: false,
      },
    };
  },
  methods: {
    submit() {
      if (!this.form.acceptedTerms) return;
      this.$emit('register-step-one', { ...this.form });
    },
  },
};
</script>
