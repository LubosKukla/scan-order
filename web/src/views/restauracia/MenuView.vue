<template>
  <div class="page page--restauracia-menu space-y-6">
    <MenuHero />
    <div class="mx-auto flex max-w-6xl flex-col gap-10 px-4 pb-10">
      <ProduktHolder
        v-for="group in groups"
        :key="group.id"
        :title="group.title"
        :items="group.items"
        @toggle-favorite="toggle(group.id, $event)"
        @add-to-cart="add(group.id, $event)"
      />
    </div>
    <CartBar :count="cartCount" :total="total" :table-label="tableLabel" />
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';
import MenuHero from '@/components/restauracia/MenuHero.vue';
import ProduktHolder from '@/components/restauracia/produkt/ProduktHolder.vue';
import CartBar from '@/components/restauracia/CartBar.vue';

const SAMPLE_GROUPS = [
  {
    id: 'pizza',
    title: 'Pizza',
    items: [
      {
        id: 1,
        name: 'Pizza Caroffia',
        desc: 'Paradajková omáčka, mozzarella, šunka',
        price: 4.99,
        allergens: ['lepok', 'mlieko'],
        likes: 12,
        isFav: false,
      },
      {
        id: 1,
        name: 'Quattro Formaggi',
        desc: 'Štyri druhy syra',
        price: 6.5,
        allergens: ['lepok', 'mlieko'],
        likes: 8,
        isFav: false,
      },
      {
        id: 1,
        name: 'Diavola',
        desc: 'Pikantná saláma, chilli',
        price: 6.9,
        allergens: ['lepok', 'mlieko'],
        likes: 5,
        isFav: false,
      },
    ],
  },
  {
    id: 'burgre',
    title: 'Burgery',
    items: [
      {
        id: 1,
        name: 'Burger San Giovanni',
        desc: 'Hovädzie, cheddar, jalapeños',
        price: 7.5,
        allergens: ['lepok', 'mlieko', 'sezam'],
        likes: 9,
        isFav: true,
      },
      {
        id: 1,
        name: 'BBQ Bacon Burger',
        desc: 'BBQ omáčka, slanina',
        price: 8.2,
        allergens: ['lepok', 'mlieko', 'sezam'],
        likes: 4,
        isFav: false,
      },
      {
        id: 1,
        name: 'Veggie Burger',
        desc: 'Cícerová placka, avokádo',
        price: 7.1,
        allergens: ['lepok', 'sezam'],
        likes: 6,
        isFav: false,
      },
    ],
  },
];

export default {
  name: 'RestauraciaMenuView',
  components: { MenuHero, ProduktHolder, CartBar },
  data() {
    return {
      tableLabel: 'Stôl 12',
      groups: SAMPLE_GROUPS,
    };
  },
  computed: {
    ...mapGetters('user', ['currentUser']),
    ...mapGetters('basket', ['basketItems', 'subtotal', 'total']),
    customerId() {
      return this.currentUser?.customer?.id || null;
    },
    restaurantId() {
      return (
        this.$route.params.restaurantId ||
        this.$route.query.restaurantId ||
        localStorage.getItem('restaurantId') ||
        this.currentUser?.restaurants?.[0]?.id ||
        1 // fallback na reštauráciu s ID 1
      );
    },
    cartCount() {
      return this.basketItems.reduce((sum, item) => sum + Number(item.quantity || 0), 0);
    },
  },
  created() {
    if (this.customerId) {
      this.fetchBaskets({ customerId: this.customerId });
    }
  },
  watch: {
    customerId(newVal, oldVal) {
      if (newVal && newVal !== oldVal) {
        this.fetchBaskets({ customerId: newVal });
      }
    },
  },
  methods: {
    ...mapActions('basket', ['addBasketItem', 'fetchBaskets']),
    toggle(groupId, itemId) {
      const group = this.groups.find((g) => g.id === groupId);
      if (!group) return;
      const item = group.items.find((i) => i.id === itemId);
      if (!item) return;
      item.isFav = !item.isFav;
      if (!item.likes) item.likes = 0;
      item.likes += item.isFav ? 1 : -1;
    },
    async add(groupId, itemId) {
      if (!this.customerId || !this.restaurantId) {
        // voliteľne Snackbar/login redirect
        return;
      }
      const group = this.groups.find((g) => g.id === groupId);
      const item = group?.items.find((i) => i.id === itemId);
      if (!item) return;

      await this.addBasketItem({
        customerId: this.customerId,
        restaurantId: this.restaurantId,
        menuItemId: item.id,
        quantity: 1,
        price: item.price,
      });
    },
  },
};
</script>
