<template>
  <section class="min-h-screen flex items-center justify-center py-12 px-4">
    <BaseCard class="w-full max-w-xl">
      <div class="flex flex-col items-center gap-2 text-center">
        <img src="@/assets/img/scan-order-logo-black.png" alt="EasyOrder" class="h-full max-w-2xs w-[90%] px-8" />
        <p class="mt-4 text-2xl font-semibold text-deep">Nastavenie nového hesla</p>
        <p class="text-sm text-deep/70">Zadajte nové heslo k vášmu účtu</p>
      </div>

      <form class="mt-8 space-y-2" @submit.prevent="submit">
        <BaseInput
          id="new-password"
          v-model="form.password"
          type="password"
          label="Nové heslo"
          placeholder="Zadajte nové heslo"
          :required="true"
          :error="errors.password"
          @input="revalidateField('password')"
        />
        <BaseInput
          id="new-password-confirm"
          v-model="form.passwordConfirm"
          type="password"
          label="Zopakujte nové heslo"
          placeholder="Zadajte heslo znova"
          :required="true"
          :error="errors.passwordConfirm"
          @input="revalidateField('passwordConfirm')"
        />

        <BaseButton type="submit" class="w-full justify-center mt-6">Uložiť nové heslo</BaseButton>

        <div class="relative text-center text-sm text-deep/60 pt-4">
          <div class="flex items-center gap-4">
            <div class="flex-1 h-px bg-deep/15"></div>
            <span class="text-deep font-medium">alebo</span>
            <div class="flex-1 h-px bg-deep/15"></div>
          </div>
        </div>

        <p class="text-center text-sm text-deep">
          Späť na
          <router-link to="/admin/login" class="font-semibold text-primary hover:text-primary/80">
            prihlásenie
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
import { useSnackbar } from '../../composables/useSnackbar';

const snackbar = useSnackbar();

export default {
  name: 'AdminZmenaZabudnutehoHeslaPoEmailiView',
  components: { BaseCard, BaseInput, BaseButton },
  data() {
    return {
      form: {
        password: '',
        passwordConfirm: '',
      },
      errors: {
        password: '',
        passwordConfirm: '',
      },
    };
  },
  methods: {
    revalidateField(field) {
      this.errors[field] = this.getFieldError(field);
      if (field === 'password') {
        this.errors.passwordConfirm = this.getFieldError('passwordConfirm');
      }
    },
    getFieldError(field) {
      const value = this.form[field].trim();
      if (field === 'password') {
        if (!value) return 'Heslo je povinné.';
        if (value.length < 8) return 'Heslo musí mať aspoň 8 znakov.';
        return '';
      }
      if (field === 'passwordConfirm') {
        if (!value) return 'Potvrdenie hesla je povinné.';
        if (value !== this.form.password) return 'Heslá sa musia zhodovať.';
        return '';
      }
      return '';
    },
    validateForm() {
      const fields = ['password', 'passwordConfirm'];
      let valid = true;
      fields.forEach((field) => {
        const message = this.getFieldError(field);
        this.errors[field] = message;
        if (message) valid = false;
      });
      return valid;
    },
    submit() {
      if (!this.validateForm()) {
        snackbar.notify({
          message: 'Skontrolujte vyplnené heslá.',
          variant: 'danger',
        });
        return;
      }

      snackbar.notify({
        message: 'Heslo bolo úspešne aktualizované.',
        variant: 'success',
      });
      this.$emit('reset-password', { password: this.form.password });
      this.$router.push({ name: 'admin-login' });
    },
  },
};
</script>
