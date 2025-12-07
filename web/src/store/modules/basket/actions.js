import axios from 'axios';
import useSnackbar from '@/composables/useSnackbar';

const snackbar = useSnackbar();

function getCustomerId(rootState, customerId) {
  if (customerId) return customerId;
  const current = rootState.user?.user;
  return current?.customer?.id || null;
}

const actions = {
  async fetchBaskets({ commit, rootState, dispatch }, payload = {}) {
    const customerId = getCustomerId(rootState, payload.customerId);
    if (!customerId) {
      commit('RESET');
      snackbar.notify({ message: 'Chýba zákazník.', variant: 'warning' });
      return [];
    }

    commit('SET_LOADING', true);
    commit('SET_ERROR', null);

    try {
      await axios.get('/sanctum/csrf-cookie');
      const { data } = await axios.get(`/api/customers/${customerId}/baskets`);
      commit('SET_BASKETS', data);

      const active = data.find((b) => b.status === 'open') || data[0] || null;
      commit('SET_ACTIVE_BASKET', active?.id || null);

      if (active) {
        await dispatch('fetchBasketItems', { customerId, basketId: active.id });
      } else {
        commit('SET_ITEMS', []);
      }

      return data;
    } catch (error) {
      commit('SET_ERROR', error?.response?.data?.message || 'Nepodarilo sa načítať košíky.');
      snackbar.notify({ message: 'Nepodarilo sa načítať košík.', variant: 'danger' });
      return [];
    } finally {
      commit('SET_LOADING', false);
    }
  },

  async addBasketItem({ dispatch, rootState }, payload) {
    const customerId = getCustomerId(rootState, payload.customerId);
    const restaurantId = payload.restaurantId;
    if (!customerId || !restaurantId) return false;

    const body = {
      menu_item_id: payload.menuItemId,
      item_variant_id: payload.itemVariantId || null,
      quantity: payload.quantity || 1,
      price: payload.price,
      note: payload.note || null,
    };

    try {
      await axios.post(`/api/customers/${customerId}/restaurants/${restaurantId}/basket/items`, body);
      await dispatch('fetchBaskets', { customerId });
      snackbar.notify({ message: 'Položka bola pridaná do košíka.', variant: 'success' });
      return true;
    } catch (error) {
      snackbar.notify({
        message: error?.response?.data?.message || 'Nepodarilo sa pridať do košíka.',
        variant: 'danger',
      });
      return false;
    }
  },

  async fetchBasketItems({ commit, rootState }, payload = {}) {
    const customerId = getCustomerId(rootState, payload.customerId);
    const basketId = payload.basketId;
    if (!customerId || !basketId) {
      commit('SET_ITEMS', []);
      return [];
    }

    commit('SET_LOADING', true);
    commit('SET_ERROR', null);

    try {
      await axios.get('/sanctum/csrf-cookie');
      const { data } = await axios.get(`/api/customers/${customerId}/baskets/${basketId}/items`);
      commit('SET_ITEMS', data.items || []);
      commit('SET_ACTIVE_BASKET', data.basket?.id || basketId);
      return data.items || [];
    } catch (error) {
      commit('SET_ERROR', 'Nepodarilo sa načítať položky košíka.');
      snackbar.notify({ message: 'Nepodarilo sa načítať položky.', variant: 'danger' });
      return [];
    } finally {
      commit('SET_LOADING', false);
    }
  },

  async updateBasketItem({ dispatch, rootState }, payload) {
    const customerId = getCustomerId(rootState, payload.customerId);
    const basketId = payload.basketId;
    const itemId = payload.itemId;
    const body = payload.data;

    if (!customerId || !basketId || !itemId) return false;

    try {
      await axios.put(`/api/customers/${customerId}/baskets/${basketId}/items/${itemId}`, body);
      await dispatch('fetchBasketItems', { customerId, basketId });
      snackbar.notify({ message: 'Položka aktualizovaná.', variant: 'success' });
      return true;
    } catch (error) {
      snackbar.notify({ message: 'Úprava položky zlyhala.', variant: 'danger' });
      return false;
    }
  },

  async removeBasketItem({ dispatch, rootState }, payload) {
    const customerId = getCustomerId(rootState, payload.customerId);
    const basketId = payload.basketId;
    const itemId = payload.itemId;

    if (!customerId || !basketId || !itemId) return false;

    try {
      await axios.delete(`/api/customers/${customerId}/baskets/${basketId}/items/${itemId}`);
      await dispatch('fetchBasketItems', { customerId, basketId });
      snackbar.notify({ message: 'Položka odstránená.', variant: 'success' });
      return true;
    } catch (error) {
      snackbar.notify({ message: 'Odstránenie položky zlyhalo.', variant: 'danger' });
      return false;
    }
  },
};

export default actions;
