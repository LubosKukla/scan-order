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
  async fetchOptions(data) {
    var commit = data.commit;

    try {
      var typesRes = await axios.get('/api/types/restaurant');
      var kitchensRes = await axios.get('/api/types/kitchen');

      var restaurantTypes = (typesRes.data && typesRes.data.types) || [];
      var cuisineTypes = (kitchensRes.data && kitchensRes.data.kitchens) || [];

      commit('SET_RESTAURANT_TYPES', restaurantTypes);
      commit('SET_CUISINE_TYPES', cuisineTypes);

      return { restaurantTypes: restaurantTypes, cuisineTypes: cuisineTypes };
    } catch (err) {
      var fallback = [{ type: 'Ostatné' }];
      commit('SET_RESTAURANT_TYPES', fallback);
      commit('SET_CUISINE_TYPES', fallback);
      snackbar.notify({ message: 'Nepodarilo sa načítať typy.', variant: 'danger' });
      return { restaurantTypes: fallback, cuisineTypes: fallback };
    }
  },

  async fetchProfile(data, restaurantId) {
    var commit = data.commit;
    var rootState = data.rootState;

    var id = getRestaurantId(rootState, restaurantId);
    if (!id) {
      commit('SET_PROFILE', null);
      snackbar.notify({ message: 'Chýba ID reštaurácie.', variant: 'info' });
      return null;
    }

    try {
      var res = await axios.get('/api/restaurants/' + id + '/settings');
      var profile = res.data && res.data.profile ? res.data.profile : null;
      commit('SET_PROFILE', profile);
      return profile;
    } catch (err) {
      commit('SET_PROFILE', null);
      snackbar.notify({
        message: err && err.response && err.response.data && err.response.data.message,
        variant: 'danger',
      });
      return null;
    }
  },

  async saveProfile(data, payload) {
    var commit = data.commit;
    var rootState = data.rootState;

    var profile = payload && payload.profile ? payload.profile : payload;
    var restaurantId = payload && payload.restaurantId;

    if (!profile) return false;

    var id = getRestaurantId(rootState, restaurantId);
    if (!id) {
      snackbar.notify({ message: 'Chýba ID reštaurácie.', variant: 'info' });
      return false;
    }

    try {
      await axios.get('/sanctum/csrf-cookie');
      var res = await axios.put('/api/restaurants/' + id + '/settings', profile);
      var saved = res.data && res.data.profile ? res.data.profile : profile;
      commit('SET_PROFILE', saved);
      snackbar.notify({ message: 'Profil reštaurácie bol uložený.', variant: 'success' });
      return saved;
    } catch (err) {
      snackbar.notify({
        message: err && err.response && err.response.data && err.response.data.message,
        variant: 'danger',
      });
      return false;
    }
  },

  async setTemporaryClosure(data, payload) {
    var rootState = data.rootState;

    var restaurantId = payload && payload.restaurantId;
    var closed = payload && typeof payload.closed === 'boolean' ? payload.closed : false;

    var id = getRestaurantId(rootState, restaurantId);
    if (!id) {
      snackbar.notify({ message: 'Chýba ID reštaurácie.', variant: 'info' });
      return null;
    }

    try {
      await axios.get('/sanctum/csrf-cookie');
      var res = await axios.post('/api/restaurants/' + id + '/temporary-close', { closed: closed });
      snackbar.notify({
        message: res.data && res.data.message,
        variant: 'info',
      });
      return res.data;
    } catch (err) {
      snackbar.notify({
        message: err && err.response && err.response.data && err.response.data.message,
        variant: 'danger',
      });
      return null;
    }
  },

  async setAccountStatus(data, payload) {
    var rootState = data.rootState;
    var restaurantId = payload && payload.restaurantId;
    var active = payload && typeof payload.active === 'boolean' ? payload.active : true;

    var id = getRestaurantId(rootState, restaurantId);
    if (!id) {
      snackbar.notify({ message: 'Chýba ID reštaurácie.', variant: 'info' });
      return false;
    }

    try {
      await axios.get('/sanctum/csrf-cookie');
      var url = '/api/restaurants/' + id + (active ? '/activate' : '/deactivate');
      var res = await axios.post(url);
      snackbar.notify({
        message: res.data && res.data.message,
        variant: 'info',
      });
      return true;
    } catch (err) {
      snackbar.notify({
        message: err && err.response && err.response.data && err.response.data.message,
        variant: 'danger',
      });
      return false;
    }
  },

  async deleteAccount(data, payload) {
    var rootState = data.rootState;
    var restaurantId = payload && payload.restaurantId;

    var id = getRestaurantId(rootState, restaurantId);
    if (!id) {
      snackbar.notify({ message: 'Chýba ID reštaurácie.', variant: 'info' });
      return false;
    }

    try {
      await axios.get('/sanctum/csrf-cookie');
      var res = await axios.post('/api/restaurants/' + id + '/delete', {
        restaurant_name: payload.restaurantName,
        password: payload.password,
      });
      snackbar.notify({
        message: res.data && res.data.message,
        variant: 'danger',
      });
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
