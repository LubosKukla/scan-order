const mutations = {
  SET_REVIEWS(state, reviews) {
    state.items = Array.isArray(reviews) ? reviews : [];
  },
  SET_LOADING(state, value) {
    state.loading = !!value;
  },
  SET_STATS(state, stats) {
    state.stats = stats || {
      average_rating: 0,
      total_reviews: 0,
      reviews_this_month: 0,
      response_rate: 0,
    };
  },
};

export default mutations;
