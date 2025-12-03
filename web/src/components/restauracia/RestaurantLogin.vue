<template>
  <router-link
    to="/restauracia/login"
    aria-label="Prihlásiť sa"
    class="login-badge bg-primary/5 text-white ring-primary/10"
  >
    <FontAwesomeIcon :icon="faUser" class="login-icon" />
  </router-link>
</template>

<script>
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { faUser } from '@fortawesome/free-solid-svg-icons';

export default {
  name: 'RestaurantBadge',
  components: { FontAwesomeIcon },
  data() {
    return { faUser };
  },
};
</script>

<style scoped lang="scss">
.login-badge {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.5rem 0.5rem;
  border-radius: 1rem;
  color: white;
  backdrop-filter: blur(4px);
  box-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);
}

.login-icon {
  width: 1rem;
  height: 1rem;
}
</style>
