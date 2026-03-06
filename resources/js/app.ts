import axios from 'axios';
import { createApp } from 'vue';

import { initializeTheme } from '@/composables/useAppearance';
import App from './App.vue';
import router from './router';
import '../css/app.css';

// Setup axios CSRF token
const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
if (token) {
    axios.defaults.headers.common['X-CSRF-TOKEN'] = token;
}
axios.defaults.withCredentials = true;

const app = createApp(App);
app.use(router);
app.mount('#app');

initializeTheme();
