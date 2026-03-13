import axios from 'axios';
import { computed, ref } from 'vue';
import { useNavItems } from '@/composables/useNavItems';

const user        = ref<Record<string, any> | null>(null);
const initialized = ref(false);

// ── Duplicate session state (shared across app) ───────────────────────────
export const duplicateSessionData = ref<{
    show: boolean;
    email: string;
    password: string;
    remember: boolean;
    attempts: number;
}>({ show: false, email: '', password: '', remember: false, attempts: 0 });

async function refreshCsrfToken() {
    const res = await fetch('/api/csrf-token');
    const data = await res.json();
    if (data.csrf_token) {
        let meta = document.querySelector('meta[name="csrf-token"]');
        if (meta) {
            meta.setAttribute('content', data.csrf_token);
        } else {
            meta = document.createElement('meta');
            meta.setAttribute('name', 'csrf-token');
            meta.setAttribute('content', data.csrf_token);
            document.head.appendChild(meta);
        }
    }
}

export function useAuth() {
    async function fetchUser() {
        if (initialized.value) return;
        try {
            const res = await axios.get('/api/user');
            user.value = res.data;
        } catch {
            user.value = null;
        } finally {
            initialized.value = true;
        }
    }

    async function login(email: string, password: string, remember = false) {
        try {
            const res = await axios.post('/login', { email, password, remember });
            user.value = res.data.user;
            await refreshCsrfToken();
        } catch (err: any) {
            const data = err.response?.data;

            // Duplicate session detected — show modal
            if (err.response?.status === 409 && data?.status === 'duplicate_session') {
                duplicateSessionData.value = {
                    show: true,
                    email,
                    password,
                    remember,
                    attempts: data.attempts ?? 1,
                };
                return; // Don't throw — let the modal handle it
            }

            // Suspended account
            if (err.response?.status === 403 && data?.status === 'suspended') {
                throw { type: 'suspended', message: data.message };
            }

            throw err;
        }
    }

    /** Called when user confirms force-login from the duplicate-session modal. */
    async function forceLogin() {
        const { email, password, remember } = duplicateSessionData.value;
        duplicateSessionData.value.show = false;
        const res = await axios.post('/login', { email, password, remember, force: true });
        user.value = res.data.user;
        useNavItems().resetNavItems();
        await refreshCsrfToken();
    }

    async function register(
        name: string,
        email: string,
        password: string,
        password_confirmation: string,
    ) {
        const res = await axios.post('/register', { name, email, password, password_confirmation});
        user.value = res.data.user;
        await refreshCsrfToken();
    }

    async function logout() {
        await axios.post('/logout');
        user.value = null;
        useNavItems().resetNavItems();
        await refreshCsrfToken();
    }

    const isAuthenticated = computed(() => !!user.value);

    function hasRole(role: string) {
        return user.value?.roles?.some((r: any) => r.name === role) ?? false;
    }

    return {
        user,
        initialized,
        isAuthenticated,
        fetchUser,
        login,
        forceLogin,
        register,
        logout,
        hasRole,
    };
}

