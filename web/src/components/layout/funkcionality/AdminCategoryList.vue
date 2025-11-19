<template>
  <BaseCard class="space-y-4 h-fit">
    <p class="text-base font-semibold text-deep">Kategórie</p>
    <nav class="flex flex-col gap-2" aria-label="Kategórie menu">
      <button
        v-for="category in categories"
        :key="category.id"
        class="flex cursor-pointer items-center justify-between rounded-xl border px-4 py-2 text-sm font-semibold transition hover:border-primary/40"
        :class="category.id === modelValue ? 'bg-primary text-white border-transparent' : 'border-ink/10 text-deep'"
        @click="handleCategoryClick(category.id)"
      >
        <div class="flex items-center gap-3">
          <span class="text-sm">
            <font-awesome-icon :icon="iconComponent(category.iconKey)" />
          </span>
          <span class="text-sm">{{ category.name }}</span>
        </div>
        <span class="text-sm">{{ category.count }}</span>
      </button>
    </nav>
    <BaseButton variant="secondary" class="w-full justify-center" icon="add" @click="showEditor = true">
      Pridať kategóriu
    </BaseButton>

    <BaseModal v-model="showEditor" title="Správa kategórií">
      <div class="space-y-4">
        <div
          v-for="category in categories"
          :key="`editor-${category.id}`"
          class="rounded-2xl border border-deep/10 p-4 space-y-3 text-deep"
        >
          <div class="flex flex-col gap-2 md:flex-row md:items-center md:gap-4">
            <div class="flex flex-1 items-center gap-3">
              <span class="text-2xl text-deep">
                <font-awesome-icon :icon="iconComponent(category.iconKey)" />
              </span>
              <div class="flex-1 w-36">
                <BaseSelect
                  :options="iconOptions"
                  option-label-key="label"
                  option-value-key="key"
                  :modelValue="category.iconKey"
                  @update:modelValue="updateIcon(category, $event)"
                />
              </div>
            </div>
          </div>
          <BaseInput
            label="Názov kategórie"
            :modelValue="category.name"
            @update:modelValue="updateName(category, $event)"
          />
          <div class="flex items-center justify-between">
            <BaseToggle v-model="category.hidden" :label="category.hidden ? 'Skryté' : 'Zobrazené'" />
            <BaseButton variant="secondary" icon="trash" @click="removeCategory(category.id)"></BaseButton>
          </div>
        </div>
        <div class="flex justify-end gap-3 pt-2">
          <BaseButton variant="secondary" @click="showEditor = false">Späť</BaseButton>
          <BaseButton @click="saveCategories">Uložiť</BaseButton>
        </div>
      </div>
    </BaseModal>
  </BaseCard>
</template>

<script>
import BaseButton from '@/components/global/buttons/BaseButton.vue';
import BaseCard from '@/components/global/containers/BaseCard.vue';
import BaseModal from '@/components/global/containers/BaseModal.vue';
import BaseSelect from '@/components/global/inputs/BaseSelect.vue';
import BaseInput from '@/components/global/inputs/BaseInput.vue';
import BaseToggle from '@/components/global/inputs/BaseToggle.vue';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { CATEGORY_ICON_OPTIONS, resolveCategoryIcon } from '@/constants/categoryIcons';
import { useSnackbar } from '@/composables/useSnackbar';

export default {
  name: 'AdminCategoryList',
  components: { BaseButton, BaseCard, BaseModal, BaseToggle, BaseSelect, BaseInput, FontAwesomeIcon },
  props: {
    modelValue: {
      type: [String, Number],
      default: 'all',
    },
  },
  emits: ['update:modelValue'],
  data() {
    return {
      showEditor: false,
      snackbar: useSnackbar(),
      categories: [
        { id: 'all', name: 'Všetky', iconKey: 'utensils', count: 6, hidden: false },
        { id: 'pizza', name: 'Pizza', iconKey: 'pizza', count: 2, hidden: false },
        { id: 'burger', name: 'Burger', iconKey: 'burger', count: 1, hidden: false },
        { id: 'pasta', name: 'Cestoviny', iconKey: 'pasta', count: 1, hidden: false },
        { id: 'fish', name: 'Ryby', iconKey: 'fish', count: 1, hidden: false },
        { id: 'salad', name: 'Šaláty', iconKey: 'salad', count: 1, hidden: false },
        { id: 'dessert', name: 'Dezerty', iconKey: 'dessert', count: 1, hidden: false },
        { id: 'drinks', name: 'Nápoje', iconKey: 'drinks', count: 0, hidden: false },
      ],
    };
  },
  computed: {
    iconOptions() {
      return CATEGORY_ICON_OPTIONS;
    },
  },
  methods: {
    iconComponent(key) {
      return resolveCategoryIcon(key);
    },
    updateIcon(category, value) {
      category.iconKey = value;
    },
    updateName(category, value) {
      category.name = value;
    },
    handleCategoryClick(id) {
      this.$emit('update:modelValue', id);
    },
    removeCategory(id) {
      this.categories = this.categories.filter((category) => category.id !== id);
      if (this.modelValue === id && this.categories.length) {
        this.$emit('update:modelValue', this.categories[0].id);
      }
    },
    saveCategories() {
      this.snackbar.notify({
        message: 'Kategórie boli uložené.',
        variant: 'success',
      });
      this.showEditor = false;
    },
  },
};
</script>
