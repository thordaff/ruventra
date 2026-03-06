
import { createApp } from 'vue';

import { initializeTheme } from '@/composables/useAppearance';
import App from './App.vue';
import router from './router';
import '../css/app.css';

const app = createApp(App);
app.use(router);
app.mount('#app');

initializeTheme();
