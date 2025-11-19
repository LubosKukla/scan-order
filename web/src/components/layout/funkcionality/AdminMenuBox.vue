<template>
  <BaseCard noPadding class="overflow-hidden flex flex-col">
    <img :src="item.image" :alt="item.name" class="h-48 w-full object-cover" />
    <div class="flex-1 space-y-3 p-4">
      <div class="flex items-start justify-between gap-2 flex-col xl:flex-row">
        <p class="text-lg font-semibold text-deep">{{ item.name }}</p>
        <p class="text-primary font-semibold">{{ formattedPrice }}</p>
      </div>
      <div class="inline-flex items-center gap-2 rounded-lg bg-deep/90 hover:bg-deep px-3 py-1 text-xs font-semibold text-white">
        <font-awesome-icon :icon="categoryIcon" />
        <span>{{ item.categoryLabel }}</span>
      </div>
    </div>
    <hr class="border-ink/10" />
    <div class="flex items-center justify-between p-4 flex-wrap gap-4">
      <BaseToggle v-model="localActive" :label="localActive ? 'Aktívne' : 'Neaktívne'" />
      <div class="flex items-center gap-3 w-full justify-between 2xl:w-auto">
        <BaseButton variant="secondary" icon="edit" @click="showEditModal = true" />
        <BaseButton variant="secondary" icon="trash" @click="showDeleteModal = true" />
      </div>
    </div>
  </BaseCard>

  <MenuItemModal v-model="showEditModal" mode="edit" :item="item" @save="confirmEdit" />

  <BaseModal v-model="showDeleteModal" title="Odstrániť položku">
    <p class="text-deep">Naozaj chcete odstrániť položku {{ item.name }} z menu?</p>
    <template #footer>
      <BaseButton variant="secondary" @click="showDeleteModal = false">Zrušiť</BaseButton>
      <BaseButton @click="confirmDelete">Odstrániť</BaseButton>
    </template>
  </BaseModal>
</template>

<script>
import BaseCard from '@/components/global/containers/BaseCard.vue';
import BaseButton from '@/components/global/buttons/BaseButton.vue';
import BaseModal from '@/components/global/containers/BaseModal.vue';
import BaseToggle from '@/components/global/inputs/BaseToggle.vue';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { resolveCategoryIcon } from '@/constants/categoryIcons';
import { formatCurrency } from '@/utils/formatters';
import { useSnackbar } from '@/composables/useSnackbar';
import MenuItemModal from '@/components/layout/modals/MenuItemModal.vue';

export default {
  name: 'AdminMenuBox',
  components: { BaseCard, BaseButton, BaseModal, BaseToggle, FontAwesomeIcon, MenuItemModal },
  props: {
    item: {
      type: Object,
      required: true,
    },
  },
  data() {
    return {
      localActive: this.item.active,
      showEditModal: false,
      showDeleteModal: false,
      snackbar: useSnackbar(),
    };
  },
  computed: {
    formattedPrice() {
      return formatCurrency(this.item.price);
    },
    categoryIcon() {
      return resolveCategoryIcon(this.item.categoryKey);
    },
  },
  methods: {
    confirmEdit(updatedItem) {
      const name = updatedItem?.name || this.item.name;
      this.snackbar.notify({ message: `Položka „${name}“ bola aktualizovaná.`, variant: 'success' });
      this.showEditModal = false;
    },
    confirmDelete() {
      this.snackbar.notify({ message: 'Položka bola odstránená.', variant: 'success' });
      this.showDeleteModal = false;
    },
  },
  watch: {
    localActive(value) {
      this.snackbar.notify({
        message: value ? 'Položka je aktívna.' : 'Položka bola deaktivovaná.',
        variant: 'info',
      });
    },
    item: {
      deep: true,
      handler(newValue) {
        if (typeof newValue?.active === 'boolean') {
          this.localActive = newValue.active;
        }
      },
    },
  },
};
</script>
