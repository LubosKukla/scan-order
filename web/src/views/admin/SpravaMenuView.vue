<template>
  <section class="page space-y-6">
    <AdminPageHeader
      title="Správa menu"
      subtitle="Spravujte jedlá, varianty, prílohy, kategórie a všetko čo potrebujete k vytvoreniu online menu."
      action-label="Pridať jedlo"
      @action-click="showAddModal = true"
    />

    <div class="grid gap-6 lg:grid-cols-[320px_1fr]">
      <AdminCategoryList v-model="selectedCategory" />
      <div class="grid gap-6 sm:grid-cols-2 xl:grid-cols-3">
        <AdminMenuBox v-for="item in filteredItems" :key="item.id" :item="item" />
      </div>
    </div>
  </section>

  <BaseModal v-model="showAddModal" title="Pridať nové jedlo">
    <p class="text-deep">Tu bude formulár pre pridanie jedla.</p>
  </BaseModal>
</template>

<script>
import AdminPageHeader from '@/components/layout/funkcionality/AdminPageHeader.vue';
import AdminCategoryList from '@/components/layout/funkcionality/AdminCategoryList.vue';
import AdminMenuBox from '@/components/layout/funkcionality/AdminMenuBox.vue';
import BaseModal from '@/components/global/containers/BaseModal.vue';

export default {
  name: 'AdminSpravaMenuView',
  components: { AdminPageHeader, AdminCategoryList, AdminMenuBox, BaseModal },
  data() {
    return {
      showAddModal: false,
      selectedCategory: 'all',
      menuItems: [
        {
          id: 'pizza-1',
          name: 'Pizza Margherita',
          price: 8.5,
          categoryLabel: 'Pizza',
          categoryKey: 'pizza',
          image: 'https://images.unsplash.com/photo-1466978913421-dad2ebd01d17?auto=format&fit=crop&w=600&q=80',
          active: true,
        },
        {
          id: 'burger-1',
          name: 'Burger Double Cheese',
          price: 9.9,
          categoryLabel: 'Burger',
          categoryKey: 'burger',
          image: 'https://images.unsplash.com/photo-1466978913421-dad2ebd01d17?auto=format&fit=crop&w=600&q=80',
          active: true,
        },
        {
          id: 'pasta-1',
          name: 'Cestoviny Carbonara',
          price: 7.2,
          categoryLabel: 'Cestoviny',
          categoryKey: 'pasta',
          image: 'https://images.unsplash.com/photo-1481391032119-d89fee407e44?auto=format&fit=crop&w=600&q=80',
          active: false,
        },
        {
          id: 'salad-1',
          name: 'Šalát s avokádom',
          price: 6.4,
          categoryLabel: 'Šaláty',
          categoryKey: 'salad',
          image: 'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?auto=format&fit=crop&w=600&q=80',
          active: true,
        },
        {
          id: 'dessert-1',
          name: 'Čokoládová pena',
          price: 4.5,
          categoryLabel: 'Dezerty',
          categoryKey: 'dessert',
          image: 'https://images.unsplash.com/photo-1466978913421-dad2ebd01d17?auto=format&fit=crop&w=600&q=80',
          active: true,
        },
        {
          id: 'drinks-1',
          name: 'Domáda limonáda',
          price: 3.2,
          categoryLabel: 'Nápoje',
          categoryKey: 'drinks',
          image: 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=600&q=80',
          active: true,
        },
      ],
    };
  },
  computed: {
    filteredItems() {
      if (this.selectedCategory === 'all') return this.menuItems;
      return this.menuItems.filter((item) => item.categoryKey === this.selectedCategory);
    },
  },
};
</script>
