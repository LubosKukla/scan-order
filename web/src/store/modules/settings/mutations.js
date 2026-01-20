const mutations = {
  SET_PROFILE(state, profile) {
    state.profile = profile || null;
  },
  SET_RESTAURANT_TYPES(state, types) {
    state.restaurantTypes = types || [];
  },
  SET_CUISINE_TYPES(state, types) {
    state.cuisineTypes = types || [];
  },
};

export default mutations;
