import axios from 'axios';
import { useSnackbar } from '@/composables/useSnackbar';

const snackbar = useSnackbar();

const actions = {
  async prihlasenie({ commit, dispatch, getters, state }, credentials) {
    try {
      if (getters.isLoggedIn) {
        snackbar.notify({
          message: 'Už ste prihlásený ' + state.user.user.email + ', netreba sa prihlasovať znova.',
          variant: 'info',
        });
        return state.user;
      }

      await axios.get('/sanctum/csrf-cookie');
      const response = await axios.post('/login', credentials);

      // Načítanie aktuálneho používateľa po prihlásení
      const freshUser = await dispatch('fetchCurrentUser');
      const loggedUser = freshUser || response.data || { email: credentials.email };

      commit('SET_USER', loggedUser);

      snackbar.notify({
        message: 'Boli ste úspešne prihlásený.' + loggedUser.email,
        variant: 'success',
      });

      return response.data;
    } catch (error) {
      const data = error?.response?.data;
      const message = data?.message || 'Prihlásenie zlyhalo. Skontrolujte údaje a skúste znova.';

      snackbar.notify({
        message,
        variant: 'danger',
      });

      throw error;
    }
  },
  async fetchCurrentUser({ commit }) {
    try {
      await axios.get('/sanctum/csrf-cookie');
      const response = await axios.get('/api/user');
      const currentUser = response.data;
      commit('SET_USER', currentUser);
      return currentUser;
    } catch (error) {
      if (error?.response?.status === 401) {
        commit('SET_USER', null);
        return null;
      }
      throw error;
    }
  },
};

export default actions;
