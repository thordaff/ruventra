import { createRouter, createWebHistory } from 'vue-router';

import AppLayout from '@/layouts/AppLayout.vue';
import DashboardSidebarLayout from '@/layouts/DashboardSidebarLayout.vue';
import Dashboard from './pages/Dashboard.vue';
import Home from './pages/Home.vue';
import NotFound from './pages/NotFound.vue';

const routes = [
    {
        path: '/',
        component: AppLayout,
        children: [
            { path: '', name: 'Home', component: Home },
        ],
    },
    {
        path: '/dashboard',
        component: DashboardSidebarLayout,
        children: [
            { path: '', name: 'Dashboard', component: Dashboard },
            // Sub-routes dashboard pakai sidebar, tampilkan 404 jika tidak ditemukan
            { path: ':pathMatch(.*)*', name: 'DashboardNotFound', component: NotFound },
        ],
    },
    {
        path: '/settings',
        component: DashboardSidebarLayout,
        redirect: '/settings/profile',
        children: [
            { path: 'profile', name: 'SettingsProfile', component: () => import('./pages/settings/Profile.vue') },
            { path: 'password', name: 'SettingsPassword', component: () => import('./pages/settings/Password.vue') },
            { path: 'appearance', name: 'SettingsAppearance', component: () => import('./pages/settings/Appearance.vue') },
            { path: 'two-factor', name: 'SettingsTwoFactor', component: () => import('./pages/settings/TwoFactor.vue') },
        ],
    },
    // Global 404
    { path: '/:pathMatch(.*)*', name: 'NotFound', component: NotFound },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
