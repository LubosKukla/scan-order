import axios from 'axios';
import { useSnackbar } from '@/composables/useSnackbar';

const snackbar = useSnackbar();

function isValidEmail(email) {
  //vzorec na overenie email mi dala AI
  return typeof email === 'string' && /\S+@\S+\.\S+/.test(email);
}

function isValidPassword(password) {
  return typeof password === 'string' && password.length >= 8;
}

const actions = {
  async prihlasenie({ dispatch, getters, state }, credentials) {
    try {
      if (getters.isLoggedIn) {
        snackbar.notify({
          message: 'Už ste prihlásený ' + state.user.user.email + ', netreba sa prihlasovať znova.',
          variant: 'info',
        });
        return state.user;
      }

      if (!isValidEmail(credentials?.email)) {
        snackbar.notify({ message: 'Zadajte platný email.', variant: 'danger' });
        return false;
      }

      if (!isValidPassword(credentials?.password)) {
        snackbar.notify({ message: 'Heslo musí mať aspoň 8 znakov.', variant: 'danger' });
        return false;
      }

      await axios.get('/sanctum/csrf-cookie');
      const response = await axios.post('/login', credentials);

      // Načítanie aktuálneho používateľa po prihlásení
      await dispatch('fetchCurrentUser');

      snackbar.notify({
        message: 'Boli ste úspešne prihlásený. ' + state.user.user.email,
        variant: 'success',
      });

      return response.data || true;
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
  async odhlasenie({ commit }) {
    try {
      await axios.get('/sanctum/csrf-cookie');
      await axios.post('/logout');
    } catch (error) {
      // Aj keď API zlyhá odpojíme usera lokálne
      if (!error?.response || error?.response?.status !== 401) {
        throw error;
      }
    } finally {
      commit('SET_USER', null);

      snackbar.notify({
        message: 'Boli ste odhlásený.',
        variant: 'success',
      });
    }
  },
};

export default actions;
