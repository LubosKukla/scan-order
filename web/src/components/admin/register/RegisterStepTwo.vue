<template>
  <form class="space-y-1" @submit.prevent="handleSubmit">
    <div class="space-y-1">
      <BaseSelect
        :modelValue="form.restaurantType"
        :options="restaurantTypes"
        label="Typ reštaurácie"
        placeholder="Vyberte typ"
        :required="true"
        :error="errors.restaurantType"
        @update:modelValue="updateField('restaurantType', $event)"
      />
      <BaseInput
        v-if="form.restaurantType === 'Ostatné'"
        :modelValue="form.otherRestaurantType"
        label="Môj typ"
        placeholder="Napíšte vlastný typ reštaurácie"
        :error="errors.otherRestaurantType"
        @update:modelValue="updateField('otherRestaurantType', $event)"
      />
    </div>
    <div class="space-y-1">
      <BaseSelect
        :modelValue="form.cuisineType"
        :options="cuisineTypes"
        label="Typ kuchyne"
        placeholder="Vyberte typ kuchyne"
        :required="true"
        :error="errors.cuisineType"
        @update:modelValue="updateField('cuisineType', $event)"
      />
      <BaseInput
        v-if="form.cuisineType === 'Ostatné'"
        :modelValue="form.otherCuisineType"
        label="Môj typ"
        placeholder="Napíšte vlastný typ kuchyne"
        :error="errors.otherCuisineType"
        @update:modelValue="updateField('otherCuisineType', $event)"
      />
    </div>

    <div class="space-y-4">
      <BaseInput
        id="street"
        :modelValue="form.street"
        label="Ulica a číslo"
        placeholder="Hlavná 123"
        :required="true"
        :error="errors.street"
        @update:modelValue="updateField('street', $event)"
      />
      <div class="grid gap-4 md:grid-cols-2">
        <BaseInput
          id="city"
          :modelValue="form.city"
          label="Mesto"
          placeholder="Bratislava"
          :required="true"
          :error="errors.city"
          @update:modelValue="updateField('city', $event)"
        />
        <BaseInput
          id="zip"
          :modelValue="form.zip"
          label="PSČ"
          placeholder="811 01"
          :required="true"
          :error="errors.zip"
          @update:modelValue="updateField('zip', $event)"
        />
      </div>
    </div>

    <div class="space-y-3 p-2">
      <div>
        <p class="text-base font-bold text-deep">Otváracie hodiny (voliteľné)</p>
        <p class="text-sm text-deep/80">Môžete vyplniť neskôr v nastaveniach</p>
      </div>
      <div class="space-y-1 max-w-xl m-auto">
        <div v-for="day in days" :key="day.key" class="flex flex-wrap justify-end gap-3 items-center">
          <span class="text-sm font-semibold w-20 text-deep mr-auto">{{ day.label }}</span>
          <BaseTimePicker
            :modelValue="form.openingHours[day.key].from"
            :label="null"
            placeholder="--:--"
            class="max-w-xs"
            @update:modelValue="updateOpening(day.key, 'from', $event)"
          />
          <BaseTimePicker
            :modelValue="form.openingHours[day.key].to"
            :label="null"
            placeholder="--:--"
            class="max-w-xs"
            @update:modelValue="updateOpening(day.key, 'to', $event)"
          />
        </div>
      </div>
    </div>

    <BaseTextarea
      id="description"
      :modelValue="form.description"
      label="Krátky popis reštaurácie"
      placeholder="Napíšte krátky popis vašej reštaurácie..."
      rows="4"
      @update:modelValue="updateField('description', $event)"
    />

    <BaseUpload
      :modelValue="form.logo"
      label="Logo reštaurácie (voliteľné)"
      placeholder="Nahrať logo"
      @update:modelValue="updateField('logo', $event)"
      class="mb-10"
    />

    <div class="flex flex-col gap-3 md:flex-row">
      <BaseButton variant="secondary" type="button" class="flex-1 justify-center" @click="$emit('prev')">
        Späť
      </BaseButton>
      <BaseButton type="submit" class="flex-1 justify-center">Pokračovať</BaseButton>
    </div>
  </form>
</template>

<script>
import BaseInput from '../../global/inputs/BaseInput.vue';
import BaseButton from '../../global/buttons/BaseButton.vue';
import BaseSelect from '../../global/inputs/BaseSelect.vue';
import BaseTimePicker from '../../global/inputs/BaseTimePicker.vue';
import BaseTextarea from '../../global/inputs/BaseTextarea.vue';
import BaseUpload from '../../global/inputs/BaseUpload.vue';

export default {
  name: 'RegisterStepTwo',
  components: { BaseInput, BaseButton, BaseSelect, BaseTimePicker, BaseTextarea, BaseUpload },
  props: {
    form: { type: Object, required: true },
    restaurantTypes: { type: Array, required: true },
    cuisineTypes: { type: Array, required: true },
    days: { type: Array, required: true },
    errors: { type: Object, required: true },
  },
  emits: ['update-field', 'update-opening-hours', 'next', 'prev'],
  methods: {
    updateField(field, value) {
      this.$emit('update-field', { field, value });
    },
    updateOpening(day, key, value) {
      this.$emit('update-opening-hours', { day, key, value });
    },
    handleSubmit() {
      this.$emit('next');
    },
  },
};
</script>
