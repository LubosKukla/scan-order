<template>
  <section class="page page--admin-sprava-menu space-y-6">
    <AdminPageHeader
      title="Správa menu"
      subtitle="Spravujte jedlá, varianty, prílohy, kategórie a všetko čo potrebujete k vytvoreniu online menu."
      action-label="Pridať jedlo"
      @action-click="showAddModal = true"
    />
  </section>
  <BaseModal v-model="showAddModal" title="Pridať nové jedlo">
    <div class="space-y-2">
      <p class="text-deep">Pridanie nového jedla.</p>
    </div>
  </BaseModal>
</template>

<script>
import AdminPageHeader from '@/components/admin/layout/AdminPageHeader.vue';
import BaseModal from '@/components/global/containers/BaseModal.vue';

export default {
  name: 'AdminSpravaMenuView',
  components: { AdminPageHeader, BaseModal },
  data() {
    return {
      showAddModal: false,
    };
  },
};
</script>
