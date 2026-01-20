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
  async registerRestaurant({ dispatch }, form) {
    if (!form) return false;

    const planMap = {
      menu: 1,
      order: 2,
      payment: 3,
      exclusive: 4,
    };

    let planId = planMap.exclusive;
    if (form.selectedPlan) {
      const parsed = Number(form.selectedPlan);
      planId = Number.isNaN(parsed) ? planMap[form.selectedPlan] || planMap.exclusive : parsed;
    }

    const addressValue = (form.street || '').trim();
    const addressParts = addressValue.split(/\s+/).filter(Boolean);
    const numberOfBuilding = addressParts.length > 1 ? addressParts[addressParts.length - 1] : addressValue;
    const streetName = addressParts.length > 1 ? addressParts.slice(0, -1).join(' ') : addressValue;

    const restaurantType = form.restaurantType === 'Ostatné' ? form.otherRestaurantType : form.restaurantType || '';
    const cuisineType = form.cuisineType === 'Ostatné' ? form.otherCuisineType : form.cuisineType || '';

    const dayMap = {
      monday: 1,
      tuesday: 2,
      wednesday: 3,
      thursday: 4,
      friday: 5,
      saturday: 6,
      sunday: 7,
    };

    const openHours = Object.keys(dayMap).map((key) => {
      const day = form.openingHours?.[key] || {};
      const openValue = (day.from || '').trim();
      const closeValue = (day.to || '').trim();
      const isClosed = !openValue && !closeValue;
      return {
        day_of_week: dayMap[key],
        open_time: openValue || null,
        close_time: closeValue || null,
        is_closed: isClosed,
      };
    });

    const hasOpenHours = openHours.some((item) => item.open_time || item.close_time);

    try {
      await axios.get('/sanctum/csrf-cookie');
      const data = new FormData();

      data.append('name', form.restaurantName || '');
      data.append('name_boss', form.ownerName || '');
      data.append('email', form.email || '');
      data.append('phone', form.phone || '');
      data.append('password', form.password || '');
      data.append('password_confirmation', form.passwordConfirm || '');
      data.append('accept_gdpr', form.acceptedTerms ? '1' : '0');
      data.append('street', streetName || '');
      data.append('number_of_building', numberOfBuilding || '');
      data.append('PSC', form.zip || '');
      data.append('city', form.city || '');
      data.append('type_restaurant_custom', restaurantType || '');
      data.append('type_kitchen_custom', cuisineType || '');
      data.append('plan_id', planId ? String(planId) : '');
      data.append('description', form.description || '');
      data.append('number_of_tables', form.tables || '');

      if (hasOpenHours) {
        openHours.forEach((item, index) => {
          data.append(`open_hours[${index}][day_of_week]`, String(item.day_of_week));
          data.append(`open_hours[${index}][open_time]`, item.open_time || '');
          data.append(`open_hours[${index}][close_time]`, item.close_time || '');
          data.append(`open_hours[${index}][is_closed]`, item.is_closed ? '1' : '0');
        });
      }

      await axios.post('/register/restaurant', data);
      await dispatch('fetchCurrentUser');

      snackbar.notify({
        message: 'Registrácia bola úspešná.',
        variant: 'success',
      });

      return true;
    } catch (error) {
      const data = error?.response?.data;
      const message = data?.message || 'Registrácia zlyhala. Skúste to znova.';

      snackbar.notify({
        message,
        variant: 'danger',
      });

      return false;
    }
  },
  async prihlasenie({ dispatch, getters, state }, credentials) {
    if (getters.isLoggedIn) {
      await dispatch('odhlasenie');
    }
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
  async odhlasenie({ commit }, options = {}) {
    const silent = !!options.silent;
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

      if (!silent) {
        snackbar.notify({
          message: 'Boli ste odhlásený.',
          variant: 'success',
        });
      }
    }
  },
};

export default actions;
