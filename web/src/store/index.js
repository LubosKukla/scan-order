import { createStore } from 'vuex';
import user from './modules/user';
import restaurant from './modules/restaurant';
import menu from './modules/menu';
import orders from './modules/orders';
import reviews from './modules/reviews';
import subscription from './modules/subscription';
import employees from './modules/employees';
import roles from './modules/roles';
import pricing from './modules/pricing';
import settings from './modules/settings';
import basket from './modules/basket';

export default createStore({
  modules: {
    user,
    restaurant,
    menu,
    orders,
    reviews,
    subscription,
    employees,
    roles,
    pricing,
    settings,
    basket,
  },
});
