<template>
  <form class="space-y-4" @submit.prevent="handleSubmit">
    <p class="text-base font-bold text-deep">Vyberte cenový plán</p>
    <div class="rounded-3xl border border-primary/20 bg-primary/5 px-6 py-4 text-sm text-deep">
      <p class="font-semibold text-lg mb-2">Skúšobné obdobie zadarmo</p>
      <p>
        Po registracii ziskate
        <strong>3 mesiace bez poplatku</strong>
        so všetkými výhodami zvoleného plánu.
      </p>
      <p>Platba nie je potrebná hneď pri registrácii.</p>
    </div>

    <div class="space-y-2">
      <div class="grid gap-4 md:grid-cols-4">
        <button
          v-for="plan in plans"
          :key="plan.id"
          type="button"
          class="rounded-2xl border px-4 py-5 text-center transition cursor-pointer focus:outline-none"
          :class="planButtonClass(plan.id)"
          @click="selectPlan(plan.id)"
        >
          <p class="text-primary text-xs font-semibold" v-if="plan.tag">{{ plan.tag }}</p>
          <p class="text-lg font-semibold text-deep">{{ plan.name }}</p>
          <p class="text-sm text-deep/70">{{ plan.label }}</p>
          <p class="mt-3 text-2xl font-bold text-deep">{{ plan.price }}</p>
          <p class="text-xs text-deep/60">/ mesiac</p>
        </button>
      </div>
      <p v-if="errors.selectedPlan" class="text-xs font-semibold text-danger px-2">{{ errors.selectedPlan }}</p>
    </div>

    <div class="space-y-1">
      <BaseInput
        id="tables"
        :modelValue="form.tables"
        type="number"
        label="Počet stolov (orientačne)"
        placeholder="Zadajte približný počet stolov"
        :error="errors.tables"
        @update:modelValue="updateField('tables', $event)"
      />
      <p class="text-xs text-deep/60 px-2">Údaje môžete neskôr upraviť v nastaveniach.</p>
    </div>

    <div
      class="rounded-2xl border border-deep/10 bg-ink/40 px-4 py-3 text-sm text-deep flex items-center gap-3 mt-8 mb-14"
    >
      <span class="text-primary text-xl">💳</span>
      <div>
        <p class="font-semibold">Spôsob platby: Stripe</p>
        <p class="text-xs text-deep/60">Bezpečná platobná brána pre online platby</p>
      </div>
    </div>

    <div class="flex flex-col gap-3 md:flex-row">
      <BaseButton variant="secondary" type="button" class="flex-1 justify-center" @click="$emit('prev')">
        Späť
      </BaseButton>
      <BaseButton type="submit" class="flex-1 justify-center">Dokončiť registráciu</BaseButton>
    </div>
  </form>
</template>

<script>
import BaseInput from '../../global/inputs/BaseInput.vue';
import BaseButton from '../../global/buttons/BaseButton.vue';

export default {
  name: 'RegisterStepThree',
  components: { BaseInput, BaseButton },
  props: {
    form: { type: Object, required: true },
    plans: { type: Array, required: true },
    errors: { type: Object, required: true },
  },
  emits: ['update-field', 'prev', 'finish'],
  methods: {
    updateField(field, value) {
      this.$emit('update-field', { field, value });
    },
    selectPlan(id) {
      this.updateField('selectedPlan', id);
    },
    planButtonClass(id) {
      const isSelected = this.form.selectedPlan === id;
      if (isSelected) {
        return 'border-primary bg-primary/5 text-primary shadow-[0_6px_16px_rgba(15,160,170,0.15)]';
      }
      return this.errors.selectedPlan
        ? 'border-danger/40 bg-white hover:border-danger'
        : 'border-ink/10 bg-white hover:border-primary/40';
    },
    handleSubmit() {
      this.$emit('finish');
    },
  },
};
</script>
