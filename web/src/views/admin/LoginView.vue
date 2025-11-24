<template>
  <section class="min-h-screen flex items-center justify-center py-12 px-4">
    <BaseCard class="w-full max-w-xl">
      <div class="flex flex-col items-center gap-2 text-center">
        <img src="@/assets/img/scan-order-logo-black.png" alt="EasyOrder" class="h-full max-w-2xs w-[90%] px-8" />
        <p class="mt-4 text-2xl font-semibold text-deep">Vitajte späť!</p>
        <p class="text-sm text-deep/70">Prihláste sa do svojho účtu</p>
      </div>

      <form class="mt-8 space-y-6" @submit.prevent="submit()">
        <div class="mb-1">
          <BaseInput id="email" v-model="form.email" type="email" placeholder="vas.email@priklad.sk" label="Email" />
        </div>

        <div class="mb-3">
          <BaseInput id="password" v-model="form.password" type="password" placeholder="Zadajte heslo" label="Heslo" />
        </div>

        <div class="text-right">
          <router-link
            to="/admin/zabudnute-heslo"
            class="text-sm font-semibold text-primary hover:text-primary/80 hover:underline"
          >
            Zabudli ste heslo?
          </router-link>
        </div>

        <BaseButton icon="login" type="submit" class="w-full justify-center">Prihlásiť sa</BaseButton>

        <div class="relative text-center text-sm text-deep/60 pt-4">
          <div class="flex items-center gap-4">
            <div class="flex-1 h-px bg-deep/15"></div>
            <span class="text-deep font-medium">alebo</span>
            <div class="flex-1 h-px bg-deep/15"></div>
          </div>
        </div>

        <p class="text-center text-sm text-deep">
          Ešte nemáte účet?
          <router-link to="/admin/register" class="font-semibold text-primary hover:text-primary/80">
            Zaregistrujte sa
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
import axios from 'axios';
import user from '@/store/user';

export default {
  name: 'AdminLoginView',
  components: { BaseCard, BaseInput, BaseButton },
  data() {
    return {
      form: {
        email: '',
        password: '',
      },
    };
  },
  methods: {
    async submit() {
      console.log('vypiš zmrde :>> ');
      await axios.get('/sanctum/csrf-cookie');
      try {
        const response = await axios.post('/login', this.form);
        console.log('Prihlásenie úspešné:', response.data);

        user.actions.setUser(this.$store, response.data.user);
      } catch (error) {
        console.error('Chyba pri prihlásení:', error);
      }
    },
  },
};
</script>
