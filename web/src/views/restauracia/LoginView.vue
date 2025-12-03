<template>
  <div class="my-32 flex items-center justify-center px-4 py-10">
    <BaseCard class="w-full max-w-md space-y-6">
      <header class="space-y-2 text-center">
        <p class="text-sm font-semibold text-primary/80 uppercase tracking-wide">Reštaurácia</p>
        <h1 class="text-2xl font-bold text-deep">Prihlásenie</h1>
        <p class="text-sm text-deep/70">Prihláste sa a využívajte výhody svojho účtu.</p>
      </header>

      <form class="space-y-2" @submit.prevent="onSubmit">
        <BaseInput v-model="form.email" type="email" label="Email" placeholder="restauracia@scanorder.sk" required />
        <BaseInput v-model="form.password" type="password" label="Heslo" placeholder="********" required />
        <div class="flex items-center justify-between text-sm text-primary mb-8">
          <router-link to="/restauracia/zabudnute-heslo" class="font-semibold hover:text-primary/80">
            Zabudnuté heslo?
          </router-link>
        </div>
        <BaseButton class="w-full justify-center" type="submit">Prihlásiť sa</BaseButton>
      </form>

      <footer class="text-center text-sm text-deep/70">
        Nemáte účet?
        <router-link to="/restauracia/registracia" class="font-semibold text-primary hover:text-primary/80">
          Vytvoriť účet
        </router-link>
      </footer>
    </BaseCard>
  </div>
</template>

<script>
import BaseCard from '@/components/global/containers/BaseCard.vue';
import BaseInput from '@/components/global/inputs/BaseInput.vue';
import BaseButton from '@/components/global/buttons/BaseButton.vue';

export default {
  name: 'RestauraciaLoginView',
  components: { BaseCard, BaseInput, BaseButton },
  data() {
    return {
      form: { email: '', password: '' },
    };
  },
  methods: {
    async onSubmit() {
      try {
        const ok = await this.$store.dispatch('user/prihlasenie', this.form);
        if (ok) {
          this.$router.push({ name: 'restauracia-menu' });
        }
      } catch (e) {
        console.log('chyby: ', e);
      }
    },
  },
};
</script>
