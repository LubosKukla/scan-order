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
    <CartBar :count="cart.count" :total="cart.total" :table-label="cart.tableLabel" />
  </div>
</template>

<script>
import MenuHero from '@/components/restauracia/MenuHero.vue';
import ProduktHolder from '@/components/restauracia/produkt/ProduktHolder.vue';
import CartBar from '@/components/restauracia/CartBar.vue';

export default {
  name: 'RestauraciaMenuView',
  components: { MenuHero, ProduktHolder, CartBar },
  data() {
    return {
      cart: {
        count: 2,
        total: 11.98,
        tableLabel: 'Stôl 12',
      },
      groups: [
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
              id: 2,
              name: 'Quattro Formaggi',
              desc: 'Mozzarella, gorgonzola, parmezán, eidam',
              price: 6.5,
              allergens: ['lepok', 'mlieko'],
              likes: 8,
              isFav: false,
            },
            {
              id: 3,
              name: 'Quattro Formaggi',
              desc: 'Mozzarella, gorgonzola, parmezán, eidam',
              price: 6.5,
              allergens: ['lepok', 'mlieko'],
              likes: 8,
              isFav: false,
            },
          ],
        },
        {
          id: 'burgre',
          title: 'Burgery',
          items: [
            {
              id: 3,
              name: 'Burger San Giovanni',
              desc: 'Hovädzie, cheddar, jalapeños, domáca žemľa',
              price: 7.5,
              allergens: ['lepok', 'mlieko', 'sezam'],
              likes: 5,
              isFav: true,
            },
            {
              id: 4,
              name: 'BBQ Bacon Burger',
              desc: 'Hovädzie, slanina, BBQ omáčka, čedar',
              price: 8.2,
              allergens: ['lepok', 'mlieko', 'sezam'],
              likes: 3,
              isFav: false,
            },
          ],
        },
      ],
    };
  },
  methods: {
    toggle(groupId, itemId) {
      const group = this.groups.find((g) => g.id === groupId);
      if (!group) return;

      const item = group.items.find((i) => i.id === itemId);
      if (!item) return;

      item.isFav = !item.isFav;
      if (!item.likes) item.likes = 0;
      item.likes += item.isFav ? 1 : -1;
    },

    add(groupId, itemId) {
      console.log('add to cart', { groupId, itemId });
    },
  },
};
</script>
