import axios from 'axios';
import { useSnackbar } from '../composables/useSnackbar';

const snackbar = useSnackbar();

const state = () => ({
  user: null,
});

const mutations = {
  SET_USER(currentState, user) {
    currentState.user = user;
  },
};

const actions = {
  async prihlasenie({ commit }, credentials) {
    try {
      await axios.get('/sanctum/csrf-cookie');
      const response = await axios.post('/login', credentials);
      const loggedUser = response.data?.user ?? { email: credentials.email };

      commit('SET_USER', loggedUser);

      snackbar.notify({
        message: 'Boli ste úspešne prihlásený.',
        variant: 'success',
      });

      return response.data;
    } catch (error) {
      const data = error?.response?.data;
      const serverErrors = data?.errors
        ? Object.values(data.errors)
            .flat()
            .filter(Boolean)
            .join(' ')
        : '';
      const message =
        serverErrors || data?.message || data?.error || 'Prihlásenie zlyhalo. Skontrolujte údaje a skúste znova.';

      snackbar.notify({
        message,
        variant: 'danger',
      });

      throw error;
    }
  },
  setUser({ commit }, user) {
    commit('SET_USER', user);
  },
};

export default {
  namespaced: true,
  state,
  mutations,
  actions,
};
