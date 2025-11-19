<template>
  <form class="space-y-1" @submit.prevent="handleSubmit">
    <BaseInput
      id="restaurant-name"
      :modelValue="form.restaurantName"
      label="Názov reštaurácie"
      placeholder="Zadajte názov reštaurácie"
      :required="true"
      :error="errors.restaurantName"
      @update:modelValue="updateField('restaurantName', $event)"
    />
    <BaseInput
      id="owner"
      :modelValue="form.ownerName"
      label="Meno majiteľa alebo kontaktnej osoby"
      placeholder="Zadajte celé meno"
      :error="errors.ownerName"
      @update:modelValue="updateField('ownerName', $event)"
    />
    <BaseInput
      id="email"
      :modelValue="form.email"
      type="email"
      label="Email"
      placeholder="vas.email@priklad.sk"
      :required="true"
      :error="errors.email"
      @update:modelValue="updateField('email', $event)"
    />
    <BaseInput
      id="phone"
      :modelValue="form.phone"
      type="tel"
      label="Telefónne číslo"
      placeholder="+421 900 000 000"
      :required="true"
      :error="errors.phone"
      @update:modelValue="updateField('phone', $event)"
    />
    <BaseInput
      id="password"
      :modelValue="form.password"
      type="password"
      label="Heslo"
      placeholder="Vytvorte silné heslo"
      :required="true"
      :error="errors.password"
      @update:modelValue="updateField('password', $event)"
    />
    <BaseInput
      id="password-confirm"
      :modelValue="form.passwordConfirm"
      type="password"
      label="Potvrďte heslo"
      placeholder="Zopakujte heslo"
      :required="true"
      :error="errors.passwordConfirm"
      @update:modelValue="updateField('passwordConfirm', $event)"
    />

    <BaseCheckbox
      :modelValue="form.acceptedTerms"
      class="my-4 mx-2"
      :error="errors.acceptedTerms"
      @update:modelValue="updateField('acceptedTerms', $event)"
    >
      Súhlasím s
      <a href="#" class="font-semibold text-primary hover:text-primary/80 hover:underline">obchodnými podmienkami</a>
      a s
      <a href="#" class="font-semibold text-primary hover:text-primary/80 hover:underline">GDPR</a>
    </BaseCheckbox>

    <BaseButton type="submit" class="w-full justify-center">Pokračovať</BaseButton>
  </form>
</template>

<script>
import BaseInput from '../../global/inputs/BaseInput.vue';
import BaseCheckbox from '../../global/inputs/BaseCheckbox.vue';
import BaseButton from '../../global/buttons/BaseButton.vue';

export default {
  name: 'RegisterStepOne',
  components: { BaseInput, BaseCheckbox, BaseButton },
  props: {
    form: {
      type: Object,
      required: true,
    },
    errors: {
      type: Object,
      required: true,
    },
  },
  emits: ['update-field', 'next'],
  methods: {
    updateField(field, value) {
      this.$emit('update-field', { field, value });
    },
    handleSubmit() {
      this.$emit('next');
    },
  },
};
</script>
