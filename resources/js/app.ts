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

// Handle session invalidation (kicked by another login)
axios.interceptors.response.use(undefined, (error) => {
    if (
        error.response?.status === 401 &&
        error.response?.data?.status === 'session_invalidated'
    ) {
        // Clear auth state and redirect gracefully
        import('@/composables/useAuth').then(({ useAuth }) => {
            const { user } = useAuth();
            user.value = null;
        });
        router.push({ name: 'Login' });
    }
    return Promise.reject(error);
});

const app = createApp(App);
app.use(router);
app.mount('#app');

initializeTheme();
