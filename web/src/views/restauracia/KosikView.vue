<template>
  <div class="page page--restauracia-kosik">
    <div class="mx-auto flex max-w-5xl flex-col gap-6 px-4 pt-6">
      <div v-if="basketError" class="rounded-2xl bg-danger/10 px-4 py-3 text-danger">
        {{ basketError }}
      </div>
      <div v-else-if="basketLoading" class="rounded-2xl bg-ink/5 px-4 py-3 text-center text-ink/60">
        Načítavam košík…
      </div>
      <template v-else-if="basketItems.length">
        <CartItem
          v-for="item in basketItems"
          :key="item.id"
          :title="item.menu_item?.name || item.menu_item_id"
          :image="item.menu_item?.image_url"
          :price="item.price"
          :quantity="item.quantity"
          @increment="increment(item)"
          @decrement="decrement(item)"
          @remove="removeItem(item)"
        />
        <div class="flex items-center justify-center gap-2 text-deep/80">
          <FontAwesomeIcon :icon="faClock" class="h-5 w-5" />
          <span>10 – 20 min</span>
        </div>
        <CartSummary :count="basketItems.length" :subtotal="subtotal" :total="total">
          <div class="grid gap-3 pt-2 sm:grid-cols-2">
            <CartButton variant="dark" label="Zaplatím za barom" />
            <CartButton label="Zaplatím ONLINE" />
          </div>
        </CartSummary>
      </template>
      <div v-else class="rounded-2xl bg-ink/5 px-4 py-6 text-center text-ink/70">Váš košík je prázdny.</div>
    </div>
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { faClock } from '@fortawesome/free-solid-svg-icons';
import CartItem from '@/components/restauracia/cart/CartItem.vue';
import CartSummary from '@/components/restauracia/cart/CartSummary.vue';
import CartButton from '@/components/restauracia/cart/CartButton.vue';

export default {
  name: 'RestauraciaKosikView',
  components: { CartItem, CartSummary, CartButton, FontAwesomeIcon },
  data() {
    return { faClock };
  },
  computed: {
    ...mapGetters('user', ['currentUser']),
    ...mapGetters('basket', ['basketItems', 'subtotal', 'total', 'basketLoading', 'basketError', 'activeBasket']),
    customerId() {
      return this.currentUser?.customer?.id || null;
    },
  },
  created() {
    if (this.customerId) {
      this.fetchBaskets({ customerId: this.customerId });
    }
  },
  methods: {
    ...mapActions('basket', ['fetchBaskets', 'updateBasketItem', 'removeBasketItem']),
    increment(item) {
      const basketId = this.activeBasket?.id;
      if (!basketId) return;
      this.updateBasketItem({
        basketId,
        itemId: item.id,
        data: { quantity: item.quantity + 1, price: item.price, note: item.note },
      });
    },
    decrement(item) {
      const basketId = this.activeBasket?.id;
      if (!basketId) return;
      if (item.quantity <= 1) {
        this.removeItem(item);
        return;
      }
      this.updateBasketItem({
        basketId,
        itemId: item.id,
        data: { quantity: item.quantity - 1, price: item.price, note: item.note },
      });
    },
    removeItem(item) {
      const basketId = this.activeBasket?.id;
      if (!basketId) return;
      this.removeBasketItem({ basketId, itemId: item.id });
    },
  },
};
</script>
