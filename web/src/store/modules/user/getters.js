const getters = {
  currentUser: (state) => state.user,
  isLoggedIn: (state) => !!state.user,
};

export default getters;
