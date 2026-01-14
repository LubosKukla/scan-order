const state = () => ({
  items: [],
  loading: false,
  stats: {
    average_rating: 0,
    total_reviews: 0,
    reviews_this_month: 0,
    response_rate: 0,
  },
});

export default state;
