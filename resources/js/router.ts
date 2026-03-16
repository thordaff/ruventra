import { createRouter, createWebHistory } from 'vue-router';

import { useAuth } from '@/composables/useAuth';
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
            { path: 'jelajah-event', name: 'JelajahEvent', component: () => import('./pages/JelajahEvent.vue') },
            { path: 'jelajah-event/:id(\\d+)', name: 'JelajahEventDetail', component: () => import('./pages/JelajahEventDetail.vue') },
            { path: 'jelajah', redirect: { name: 'JelajahEvent' } },
        ],
    },
    {
        path: '/login',
        name: 'Login',
        component: () => import('./pages/auth/AuthPage.vue'),
        meta: { guestOnly: true },
    },
    {
        path: '/dashboard',
        component: DashboardSidebarLayout,
        meta: { requiresAuth: true },
        children: [
            { path: '', name: 'Dashboard', component: Dashboard },
            { path: 'statistics', name: 'DashboardStatistics', component: () => import('./pages/dashboard/main/Statistic.vue') },
            { path: 'revenue', name: 'DashboardRevenue', component: () => import('./pages/dashboard/main/Revenue.vue') },
            // Sub-routes dashboard pakai sidebar, tampilkan 404 jika tidak ditemukan
            { path: ':pathMatch(.*)*', name: 'DashboardNotFound', component: NotFound },
        ],
    },
    {
        path: '/settings',
        component: DashboardSidebarLayout,
        meta: { requiresAuth: true },
        redirect: '/settings/profile',
        children: [
            { path: 'profile', name: 'SettingsProfile', component: () => import('./pages/settings/Profile.vue') },
            { path: 'password', name: 'SettingsPassword', component: () => import('./pages/settings/Password.vue') },
            { path: 'appearance', name: 'SettingsAppearance', component: () => import('./pages/settings/Appearance.vue') },
            { path: 'two-factor', name: 'SettingsTwoFactor', component: () => import('./pages/settings/TwoFactor.vue') },
        ],
    },
    {
        path: '/system',
        component: DashboardSidebarLayout,
        meta: { requiresAuth: true },
        redirect: '/system/logs',
        children: [
            { path: 'logs', name: 'SystemLogs', component: () => import('./pages/system/Logs.vue') },
        ],
    },
    // Global 404
    { path: '/:pathMatch(.*)*', name: 'NotFound', component: NotFound },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

// ── Auth guard ─────────────────────────────────────────────────────────────
router.beforeEach(async (to) => {
    const { isAuthenticated, fetchUser, initialized } = useAuth();

    // Ensure we know the auth state before deciding
    if (!initialized.value) {
        await fetchUser();
    }

    // Redirect unauthenticated users away from protected routes
    if (to.meta.requiresAuth && !isAuthenticated.value) {
        return { name: 'Login', query: { redirect: to.fullPath } };
    }

    // Redirect already-authenticated users away from guest-only pages
    if (to.meta.guestOnly && isAuthenticated.value) {
        return { name: 'Dashboard' };
    }
});

export default router;
