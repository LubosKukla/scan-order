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

      <RegisterStepOne v-if="step === 1" :form="form" @update-field="updateField" @next="nextStep" />

      <RegisterStepTwo
        v-else-if="step === 2"
        :form="form"
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
        street: '',
        city: '',
        zip: '',
        openingHours: this.createOpeningHours(),
        description: '',
        logo: null,
        selectedPlan: 'menu',
        tables: '',
      },
      restaurantTypes: ['Reštaurácia', 'Bistro', 'Kaviareň', 'Bar'],
      cuisineTypes: ['Európska', 'Ázijská', 'Talianska', 'Mexická'],
      days: [
        { key: 'monday', label: 'Pondelok' },
        { key: 'tuesday', label: 'Utorok' },
        { key: 'wednesday', label: 'Streda' },
        { key: 'thursday', label: 'Štvrtok' },
        { key: 'friday', label: 'Piatok' },
        { key: 'saturday', label: 'Sobota' },
        { key: 'sunday', label: 'Nedeľa' },
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
      if (field in this.form) {
        this.form[field] = value;
      }
    },
    updateOpeningHours({ day, key, value }) {
      this.form.openingHours[day][key] = value;
    },
    nextStep() {
      if (this.step === 1 && !this.form.acceptedTerms) return;
      if (this.step < 3) this.step += 1;
    },
    goToStep(stepNumber) {
      const target = Number(stepNumber);
      if (target >= 1 && target <= 3) {
        this.step = target;
      }
    },
    finishRegistration() {
      this.$emit('register-submit', { ...this.form });
    },
  },
};
</script>
