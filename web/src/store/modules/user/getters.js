const getters = {
  currentUser: (state) => state.user,
  isAuthenticated: (state) => !!state.user,
};

export default getters;
