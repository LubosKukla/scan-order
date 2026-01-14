<template>
  <section class="min-h-screen flex items-center justify-center py-12 px-4">
    <BaseCard class="w-full max-w-4xl space-y-8">
      <div class="flex flex-col items-center">
        <img src="@/assets/img/scan-order-logo-black.png" alt="EasyOrder" class="h-full max-w-2xs w-[90%] px-8" />
      </div>

      <div class="flex w-full items-center gap-4 text-sm font-semibold text-deep/50">
        <span
          class="flex h-8 w-8 items-center justify-center rounded-full"
          :class="step >= 1 ? 'bg-primary text-white' : 'bg-deep/15 text-deep'"
        >
          1
        </span>
        <div class="h-px flex-1" :class="step >= 2 ? 'bg-primary' : 'bg-deep/15'" />
        <span
          class="flex h-8 w-8 items-center justify-center rounded-full"
          :class="step >= 2 ? 'bg-primary text-white' : 'bg-deep/15 text-deep'"
        >
          2
        </span>
        <div class="h-px flex-1" :class="step >= 3 ? 'bg-primary' : 'bg-deep/15'" />
        <span
          class="flex h-8 w-8 items-center justify-center rounded-full"
          :class="step === 3 ? 'bg-primary text-white' : 'bg-deep/15 text-deep'"
        >
          3
        </span>
      </div>

      <div class="text-center space-y-1">
        <p class="text-2xl font-semibold text-deep">
          {{ stepTitle }}
        </p>
        <p class="text-sm text-deep/70">
          {{ stepSubtitle }}
        </p>
      </div>

      <RegisterStepOne v-if="step === 1" :form="form" :errors="errors" @update-field="updateField" @next="nextStep" />
      <RegisterStepTwo
        v-else-if="step === 2"
        :form="form"
        :errors="errors"
        :restaurant-types="restaurantTypes"
        :cuisine-types="cuisineTypes"
        :days="days"
        @update-field="updateField"
        @update-opening-hours="updateOpeningHours"
        @prev="goToStep(1)"
        @next="nextStep"
      />
      <RegisterStepThree
        v-else
        :form="form"
        :errors="errors"
        :plans="plans"
        @update-field="updateField"
        @prev="goToStep(2)"
        @finish="finishRegistration"
      />

      <div class="relative text-center text-sm text-deep/60 pt-4">
        <div class="flex items-center gap-4">
          <div class="flex-1 h-px bg-deep/15"></div>
          <span class="text-deep font-medium">alebo</span>
          <div class="flex-1 h-px bg-deep/15"></div>
        </div>
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
import RegisterStepOne from '../../components/admin/register/RegisterStepOne.vue';
import RegisterStepTwo from '../../components/admin/register/RegisterStepTwo.vue';
import RegisterStepThree from '../../components/admin/register/RegisterStepThree.vue';
import { useSnackbar } from '../../composables/useSnackbar';

//rexegex code from AI
const EMAIL_REGEX = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
const PHONE_REGEX = /^\+?\d{8,14}$/;
const ZIP_REGEX = /^\d{3}\s?\d{2}$/;
const snackbar = useSnackbar();

export default {
  name: 'AdminRegisterView',
  components: { BaseCard, RegisterStepOne, RegisterStepTwo, RegisterStepThree },
  data() {
    return {
      step: 1,
      form: {
        restaurantName: '',
        ownerName: '',
        email: '',
        phone: '',
        password: '',
        passwordConfirm: '',
        acceptedTerms: false,
        restaurantType: '',
        cuisineType: '',
        otherRestaurantType: '',
        otherCuisineType: '',
        street: '',
        city: '',
        zip: '',
        openingHours: this.createOpeningHours(),
        description: '',
        logo: null,
        selectedPlan: '',
        tables: '',
      },
      errors: {
        restaurantName: '',
        ownerName: '',
        email: '',
        phone: '',
        password: '',
        passwordConfirm: '',
        acceptedTerms: '',
        restaurantType: '',
        cuisineType: '',
        otherRestaurantType: '',
        otherCuisineType: '',
        street: '',
        city: '',
        zip: '',
        tables: '',
        selectedPlan: '',
      },
      restaurantTypes: ['Reštaurácia', 'Bistro', 'Kaviareň', 'Bar', 'Ostatné'],
      cuisineTypes: ['Európska', 'Ázijská', 'Talianska', 'Mexická', 'Ostatné'],
      days: [
        { key: 'monday', label: 'Pondelok' },
        { key: 'tuesday', label: 'Utorok' },
        { key: 'wednesday', label: 'Streda' },
        { key: 'thursday', label: 'Štvrtok' },
        { key: 'friday', label: 'Piatok' },
        { key: 'saturday', label: 'Sobota' },
        { key: 'sunday', label: 'Nedela' },
      ],
      plans: [
        { id: 'menu', name: 'Menu', label: 'Základné', price: '8,46 €' },
        { id: 'order', name: 'Objednávka', label: 'Dobrá voľba', price: '38,98 €' },
        { id: 'payment', name: 'Platba', label: 'Najlepšia voľba', price: '47,97 €', tag: 'Populárne' },
        { id: 'exclusive', name: 'Exclusive', label: 'Prémiové', price: '69,96 €' },
      ],
    };
  },
  computed: {
    stepTitle() {
      if (this.step === 1) return 'Základná registrácia';
      if (this.step === 2) return 'Detaily reštaurácie';
      return 'Nastavenie systému';
    },
    stepSubtitle() {
      if (this.step === 1) return 'Vytvorte si účet pre vašu reštauráciu';
      if (this.step === 2) return 'Doplňte informácie o vašej reštaurácii';
      return 'Vyberte si plán a dokončite registráciu';
    },
  },
  methods: {
    createOpeningHours() {
      return {
        monday: { from: '', to: '' },
        tuesday: { from: '', to: '' },
        wednesday: { from: '', to: '' },
        thursday: { from: '', to: '' },
        friday: { from: '', to: '' },
        saturday: { from: '', to: '' },
        sunday: { from: '', to: '' },
      };
    },
    updateField({ field, value }) {
      if (!(field in this.form)) return;
      this.form[field] = value;
      this.revalidateField(field);
      if (field === 'password' || field === 'passwordConfirm') {
        this.revalidateField('passwordConfirm');
      }
      if (field === 'restaurantType' && value !== 'Ostatné') {
        this.form.otherRestaurantType = '';
        this.errors.otherRestaurantType = '';
      }
      if (field === 'cuisineType' && value !== 'Ostatné') {
        this.form.otherCuisineType = '';
        this.errors.otherCuisineType = '';
      }
    },
    updateOpeningHours({ day, key, value }) {
      if (this.form.openingHours[day]) {
        this.form.openingHours[day][key] = value;
      }
    },
    revalidateField(field) {
      if (!(field in this.errors)) return;
      this.errors[field] = this.getFieldError(field);
    },
    getFieldError(field) {
      const value = this.form[field];
      const normalized = value === null || value === undefined ? '' : String(value).trim();
      switch (field) {
        case 'restaurantName':
          return normalized ? '' : 'Toto pole je povinné.';
        case 'email':
          if (!normalized) return 'Email je povinný.';
          return EMAIL_REGEX.test(normalized) ? '' : 'Zadajte platný email.';
        case 'phone':
          if (!normalized) return 'Telefón je povinný.';
          return PHONE_REGEX.test(normalized.replace(/\s+/g, ''))
            ? ''
            : 'Telefón musí obsahovať 8 až 14 číslic (môže mať medzery a znak + na začiatku).';
        case 'password':
          if (!normalized) return 'Heslo je povinné.';
          if (normalized.length < 8) return 'Heslo musí mať aspoň 8 znakov.';
          return '';
        case 'passwordConfirm':
          if (!normalized) return 'Potvrdenie hesla je povinné.';
          if (value !== this.form.password) return 'Heslá sa musia zhodovať.';
          return '';
        case 'restaurantType':
        case 'cuisineType':
        case 'street':
        case 'city':
          return normalized ? '' : 'Toto pole je povinné.';
        case 'otherRestaurantType':
          return this.form.restaurantType === 'Ostatné' && !normalized ? 'Uveďte vlastný typ reštaurácie.' : '';
        case 'otherCuisineType':
          return this.form.cuisineType === 'Ostatné' && !normalized ? 'Uveďte vlastný typ kuchyne.' : '';
        case 'zip':
          if (!normalized) return 'PSČ je povinné.';
          return ZIP_REGEX.test(normalized) ? '' : 'Zadajte platné PSČ.';
        case 'acceptedTerms':
          return this.form.acceptedTerms ? '' : 'Je potrebné súhlasiť s podmienkami.';
        case 'selectedPlan':
          return this.form.selectedPlan ? '' : 'Vyberte plán.';
        case 'tables':
          if (!normalized) return '';
          return Number(value) >= 0 ? '' : 'Zadajte kladné číslo.';
        default:
          return '';
      }
    },
    validateFields(fields) {
      let valid = true;
      fields.forEach((field) => {
        const message = this.getFieldError(field);
        this.errors[field] = message;
        if (message) valid = false;
      });
      return valid;
    },
    validateStepOne() {
      return this.validateFields(['restaurantName', 'email', 'phone', 'password', 'passwordConfirm', 'acceptedTerms']);
    },
    validateStepTwo() {
      const fields = ['restaurantType', 'cuisineType', 'street', 'city', 'zip'];
      if (this.form.restaurantType === 'Ostatné') fields.push('otherRestaurantType');
      if (this.form.cuisineType === 'Ostatné') fields.push('otherCuisineType');
      return this.validateFields(fields);
    },
    validateStepThree() {
      return this.validateFields(['selectedPlan']);
    },
    notifyErrors(message) {
      snackbar.notify({
        message,
        variant: 'danger',
      });
    },
    nextStep() {
      let valid = true;
      if (this.step === 1) valid = this.validateStepOne();
      else if (this.step === 2) valid = this.validateStepTwo();

      if (!valid) {
        this.notifyErrors('Vyplňte prosím povinné údaje.');
        return;
      }

      if (this.step < 3) this.step += 1;
    },
    goToStep(stepNumber) {
      const target = Number(stepNumber);
      if (target >= 1 && target <= 3) {
        this.step = target;
      }
    },
    finishRegistration() {
      if (!this.validateStepThree()) {
        this.notifyErrors('Vyberte si plán, aby ste mohli pokračovať.');
        return;
      }

      snackbar.notify({
        message: 'Registrácia bola úspešná.',
        variant: 'success',
      });
      this.$emit('register-submit', { ...this.form });
    },
  },
};
</script>
