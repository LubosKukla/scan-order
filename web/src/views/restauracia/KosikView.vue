<template>
  <div class="page page--restauracia-kosik">
    <div class="mx-auto flex max-w-5xl flex-col gap-6 px-4 pt-6">
      <CartItem
        title="Burger Klasický"
        :price="5.99"
        :quantity="1"
        image="https://images.unsplash.com/photo-1550547660-d9450f859349?auto=format&fit=crop&w=400&q=80"
      />

      <div class="flex items-center justify-center gap-2 text-deep/80">
        <FontAwesomeIcon :icon="faClock" class="h-5 w-5" />
        <span>10 - 20 min</span>
      </div>

      <CartSummary :count="1" :subtotal="5.99" :total="5.99">
        <div class="grid gap-3 pt-2 sm:grid-cols-2">
          <CartButton variant="dark" label="Zaplatíť za barom" />
          <CartButton label="Zaplatíť ONLINE" />
        </div>
      </CartSummary>
    </div>
  </div>
</template>

<script>
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
};
</script>
