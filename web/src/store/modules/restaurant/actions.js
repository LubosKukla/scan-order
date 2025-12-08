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

function getDefault(rootGetters) {
  var d = rootGetters['restaurant/defaultHours'];
  return ensureSevenDays(d);
}

function fixTime(value) {
  if (!value || typeof value !== 'string') return '00:00';

  var parts = value.split(':');
  var h = parts[0] || '00';
  var m = parts[1] || '00';

  if (h.length === 1) h = '0' + h;
  if (m.length === 1) m = '0' + m;

  var finalValue = h + ':' + m;

  var valid = /^\d{2}:\d{2}$/.test(finalValue);
  if (!valid) return '00:00';

  return finalValue;
}

function ensureSevenDays(hours) {
  var result = [];
  var map = {};
  var i;

  if (!Array.isArray(hours)) {
    hours = [];
  }

  for (i = 0; i < hours.length; i++) {
    var h = hours[i];

    if (h && h.day_of_week !== null && h.day_of_week !== undefined) {
      map[h.day_of_week] = h;
    }
  }

  for (var d = 1; d <= 7; d++) {
    if (map[d]) {
      result.push({
        day_of_week: d,
        open_time: map[d].open_time || '00:00',
        close_time: map[d].close_time || '00:00',
        is_closed: !!map[d].is_closed,
      });
    } else {
      result.push({
        day_of_week: d,
        open_time: '00:00',
        close_time: '00:00',
        is_closed: true,
      });
    }
  }

  return result;
}

function makePayload(hours) {
  var full = ensureSevenDays(hours);

  return full.map(function (d) {
    var openValue = d.open_time || '00:00';
    var closeValue = d.close_time || '00:00';

    return {
      day_of_week: d.day_of_week,
      is_closed: !!d.is_closed,
      open_time: fixTime(openValue),
      close_time: fixTime(closeValue),
    };
  });
}

var actions = {
  async fetchOpenHours(data, restaurantId) {
    var commit = data.commit;
    var rootState = data.rootState;
    var rootGetters = data.rootGetters;

    var id = getRestaurantId(rootState, restaurantId);

    if (!id) {
      var defaults = getDefault(rootGetters);
      commit('SET_OPEN_HOURS', defaults);
      snackbar.notify({ message: 'Chýba ID reštaurácie.', variant: 'info' });
      return defaults;
    }

    try {
      var res = await axios.get('/api/restaurants/' + id + '/openhours');
      var raw = res.data.open_hours || [];
      var hours;

      if (raw.length === 0) {
        hours = getDefault(rootGetters);
      } else {
        hours = ensureSevenDays(raw);
      }

      commit('SET_OPEN_HOURS', hours);
      return hours;
    } catch (err) {
      var def2 = getDefault(rootGetters);
      commit('SET_OPEN_HOURS', def2);
      snackbar.notify({ message: 'Nepodarilo sa načítať hodiny.', variant: 'danger' });
      return def2;
    }
  },

  async saveOpenHours(data, payloadObj) {
    var commit = data.commit;
    var rootState = data.rootState;
    var rootGetters = data.rootGetters;

    var restaurantId = payloadObj.restaurantId;
    var hours = payloadObj.hours;

    var id = getRestaurantId(rootState, restaurantId);
    if (!id) {
      snackbar.notify({ message: 'Chýba ID reštaurácie.', variant: 'warning' });
      return false;
    }

    var fullHours = ensureSevenDays(hours || getDefault(rootGetters));
    var payload = makePayload(fullHours);

    try {
      await axios.get('/sanctum/csrf-cookie');
      var res = await axios.get('/api/restaurants/' + id + '/openhours');
      var existing = res.data.open_hours || [];

      if (existing.length === 0) {
        await axios.post('/api/restaurants/' + id + '/openhours', { open_hours: payload });
      } else {
        await axios.put('/api/restaurants/' + id + '/openhours', { open_hours: payload });
      }

      commit('SET_OPEN_HOURS', fullHours);
      snackbar.notify({ message: 'Hodiny uložené.', variant: 'success' });
      return fullHours;
    } catch (err) {
      snackbar.notify({ message: 'Nepodarilo sa uložiť hodiny.', variant: 'danger' });
      return false;
    }
  },

  async deleteOpenHours(data, payloadObj) {
    var commit = data.commit;
    var rootState = data.rootState;
    var rootGetters = data.rootGetters;

    var restaurantId = payloadObj && payloadObj.restaurantId;
    var id = getRestaurantId(rootState, restaurantId);

    if (!id) {
      snackbar.notify({ message: 'Chýba ID reštaurácie.', variant: 'warning' });
      return false;
    }

    try {
      await axios.get('/sanctum/csrf-cookie');
      await axios.delete('/api/restaurants/' + id + '/openhours');
      var defaults = getDefault(rootGetters);
      commit('SET_OPEN_HOURS', defaults);
      snackbar.notify({ message: 'Otváracie hodiny boli vymazané.', variant: 'success' });
      return defaults;
    } catch (err) {
      snackbar.notify({ message: 'Nepodarilo sa vymazať otváracie hodiny.', variant: 'danger' });
      return false;
    }
  },

  async removeDayOpenHours(data, payloadObj) {
    var dispatch = data.dispatch;
    var restaurantId = payloadObj.restaurantId;
    var hours = payloadObj.hours;
    var dayOfWeek = payloadObj.dayOfWeek;

    if (!dayOfWeek) return false;

    var updated = ensureSevenDays(hours).map(function (d) {
      if (d.day_of_week === dayOfWeek) {
        d.is_closed = true;
        d.open_time = '00:00';
        d.close_time = '00:00';
      }
      return d;
    });

    return dispatch('saveOpenHours', {
      restaurantId: restaurantId,
      hours: updated,
    });
  },
};

export default actions;
