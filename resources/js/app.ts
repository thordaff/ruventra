import axios from 'axios';
import { createApp } from 'vue';

import { initializeTheme } from '@/composables/useAppearance';
import App from './App.vue';
import router from './router';
import '../css/app.css';

axios.defaults.withCredentials = true;
axios.interceptors.request.use((config) => {
    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (token) {
        config.headers['X-CSRF-TOKEN'] = token;
    }
    return config;
});

const app = createApp(App);
app.use(router);
app.mount('#app');

initializeTheme();
