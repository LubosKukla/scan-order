const getters = {
  allBaskets: (state) => state.baskets,
  activeBasket: (state) => state.baskets.find((b) => b.id === state.activeBasketId) || null,
  basketItems: (state) => state.items,
  subtotal: (state, getters) => getters.activeBasket?.subtotal || 0,
  total: (state, getters) => getters.activeBasket?.total || 0,
  basketLoading: (state) => state.isLoading,
  basketError: (state) => state.error,
};

export default getters;
