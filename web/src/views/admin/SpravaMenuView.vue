<template>
  <section class="page space-y-6">
    <AdminPageHeader
      title="Správa menu"
      subtitle="Spravujte jedlá, varianty, prílohy, kategórie a všetko čo potrebujete k vytvoreniu online menu."
      action-label="Pridať jedlo"
      @action-click="showAddModal = true"
    />
    <div class="grid gap-6 lg:grid-cols-[2fr,1fr]">
      <AdminCategoryList v-model="selectedCategory" />
    </div>
  </section>
  <BaseModal v-model="showAddModal" title="Pridať nové jedlo">
    <div class="space-y-2">
      <p class="text-deep">Pridanie nového jedla.</p>
    </div>
  </BaseModal>
</template>

<script>
import AdminPageHeader from '@/components/layout/funkcionality/AdminPageHeader.vue';
import AdminCategoryList from '@/components/layout/funkcionality/AdminCategoryList.vue';
import BaseModal from '@/components/global/containers/BaseModal.vue';

export default {
  name: 'AdminSpravaMenuView',
  components: { AdminPageHeader, AdminCategoryList, BaseModal },
  data() {
    return {
      showAddModal: false,
      selectedCategory: 'all',
    };
  },
};
</script>
