const mutations = {
  SET_LOADING(state, flag) {
    state.isLoading = !!flag;
  },
  SET_ERROR(state, message) {
    state.error = message || null;
  },
  SET_BASKETS(state, baskets) {
    state.baskets = Array.isArray(baskets) ? baskets : [];
  },
  SET_ACTIVE_BASKET(state, basketId) {
    state.activeBasketId = basketId || null;
  },
  SET_ITEMS(state, items) {
    state.items = Array.isArray(items) ? items : [];
  },
  RESET(state) {
    state.baskets = [];
    state.items = [];
    state.activeBasketId = null;
    state.isLoading = false;
    state.error = null;
  },
};

export default mutations;
