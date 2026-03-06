import { createRouter, createWebHistory } from 'vue-router';

import AppLayout from '@/layouts/AppLayout.vue';
import Dashboard from './pages/Dashboard.vue';
import Home from './pages/Home.vue';

const routes = [
    {
        path: '/',
        component: AppLayout,
        children: [
            { path: '', name: 'Home', component: Home },
            { path: 'dashboard', name: 'Dashboard', component: Dashboard },
        ],
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
