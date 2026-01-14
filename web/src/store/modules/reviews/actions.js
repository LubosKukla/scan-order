import axios from 'axios';
import useSnackbar from '@/composables/useSnackbar';

var snackbar = useSnackbar();

function getRestaurantId(rootState, restaurantId) {
  if (restaurantId) return restaurantId;
  if (rootState && rootState.user && rootState.user.user && rootState.user.user.restaurants) {
    return rootState.user.user.restaurants[0].id;
  }
  return null;
}

const actions = {
  async fetchReviews(data, filters) {
    var commit = data.commit;
    var rootState = data.rootState;

    var id = getRestaurantId(rootState, filters && filters.restaurantId);
    if (!id) {
      commit('SET_REVIEWS', []);
      snackbar.notify({ message: 'Chýba ID reštaurácie.', variant: 'info' });
      return [];
    }

    commit('SET_LOADING', true);
    try {
      var params = {};
      if (filters && filters.search && filters.search.trim()) {
        params.search = filters.search.trim();
      }
      if (filters && filters.rating && filters.rating !== 'all') {
        params.rating = filters.rating;
      }
      if (filters && filters.period && filters.period !== 'all') {
        params.period = filters.period;
      }
      if (filters && filters.type && filters.type !== 'all') {
        params.type = filters.type;
      }

      var res = await axios.get('/api/restaurants/' + id + '/reviews', { params: params });
      var raw = (res.data && res.data.reviews) || [];
      commit('SET_REVIEWS', raw);
      return raw;
    } catch (err) {
      commit('SET_REVIEWS', []);
      snackbar.notify({
        message: err && err.response && err.response.data && err.response.data.message,
        variant: 'danger',
      });
      return [];
    } finally {
      commit('SET_LOADING', false);
    }
  },
  async fetchReviewStats(data, filters) {
    var commit = data.commit;
    var rootState = data.rootState;

    var id = getRestaurantId(rootState, filters && filters.restaurantId);
    if (!id) {
      commit('SET_STATS', {
        average_rating: 0,
        total_reviews: 0,
        reviews_this_month: 0,
        response_rate: 0,
      });
      snackbar.notify({ message: 'Chýba ID reštaurácie.', variant: 'info' });
      return false;
    }

    try {
      var params = {};
      if (filters && filters.type && filters.type !== 'all') {
        params.type = filters.type;
      }
      var res = await axios.get('/api/restaurants/' + id + '/reviews/stats', { params: params });
      commit('SET_STATS', res.data.stats);
      return true;
    } catch (err) {
      commit('SET_STATS', {
        average_rating: 0,
        total_reviews: 0,
        reviews_this_month: 0,
        response_rate: 0,
      });
      snackbar.notify({
        message: err && err.response && err.response.data && err.response.data.message,
        variant: 'danger',
      });
      return false;
    }
  },
  async respondReview(data, payload) {
    if (!payload || !payload.reviewId) {
      snackbar.notify({ message: 'Chýba ID recenzie.', variant: 'info' });
      return false;
    }
    try {
      await axios.get('/sanctum/csrf-cookie');
      await axios.post('/api/reviews/' + payload.reviewId + '/response', { text: payload.text });
      snackbar.notify({ message: 'Odpoveď bola odoslaná.', variant: 'success' });
      return true;
    } catch (err) {
      snackbar.notify({
        message: err && err.response && err.response.data && err.response.data.message,
        variant: 'danger',
      });
      return false;
    }
  },
  async updateResponse(data, payload) {
    if (!payload || !payload.reviewId) {
      snackbar.notify({ message: 'Chýba ID recenzie.', variant: 'info' });
      return false;
    }
    try {
      await axios.get('/sanctum/csrf-cookie');
      await axios.put('/api/reviews/' + payload.reviewId + '/response', { text: payload.text });
      snackbar.notify({ message: 'Odpoveď bola upravená.', variant: 'success' });
      return true;
    } catch (err) {
      snackbar.notify({
        message: err && err.response && err.response.data && err.response.data.message,
        variant: 'danger',
      });
      return false;
    }
  },
  async deleteReview(data, reviewId) {
    if (!reviewId) {
      snackbar.notify({ message: 'Chýba ID recenzie.', variant: 'info' });
      return false;
    }
    try {
      await axios.get('/sanctum/csrf-cookie');
      await axios.delete('/api/reviews/' + reviewId);
      snackbar.notify({ message: 'Recenzia bola vymazaná.', variant: 'success' });
      return true;
    } catch (err) {
      snackbar.notify({
        message: err && err.response && err.response.data && err.response.data.message,
        variant: 'danger',
      });
      return false;
    }
  },
};

export default actions;
