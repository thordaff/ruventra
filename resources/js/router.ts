import { createRouter, createWebHistory } from 'vue-router';

import Halo from './pages/Halo.vue';
import Home from './pages/Home.vue';

const routes = [
  { path: '/', name: 'Home', component: Home },
  { path: '/halo', name: 'Halo', component: Halo },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
