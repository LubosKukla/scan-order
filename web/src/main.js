import { createApp } from 'vue';
import App from './App.vue';
import router from './router';
import store from './store';
import './assets/tailwind.css';

//toto by malo fungovat na cookies

/*axios.defaults.withCredentials = true
axios.defaults.baseURL = import.meta.env.VITE_API_URL || '/'
axios.defaults.xsrfCookieName = 'XSRF-TOKEN'
axios.defaults.xsrfHeaderName = 'X-XSRF-TOKEN'

// Pomocná funkcia na čítanie cookie podľa mena
function getCookie(name) {
  if (typeof document === 'undefined') return null
  const value = `; ${document.cookie}`
  const parts = value.split(`; ${name}=`)
  if (parts.length === 2) return parts.pop().split(';').shift()
  return null
}

if (typeof window !== 'undefined') {
  axios.interceptors.request.use(
    (config) => {
      const token = getCookie('XSRF-TOKEN')
      if (token) config.headers['X-XSRF-TOKEN'] = decodeURIComponent(token)
      return config
    },
    (error) => Promise.reject(error),
  )
}*/

createApp(App).use(store).use(router).mount('#app');
