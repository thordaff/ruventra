import axios from 'axios';
import { computed, ref } from 'vue';
import { useNavItems } from '@/composables/useNavItems';

const user = ref<Record<string, any> | null>(null);
const initialized = ref(false);

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
        const res = await axios.post('/login', { email, password, remember });
        user.value = res.data.user;
        // Session regenerate saat login membuat CSRF token baru — update meta tag
        await refreshCsrfToken();
    }

    async function register(
        name: string,
        email: string,
        password: string,
        password_confirmation: string,
    ) {
        const res = await axios.post('/register', { name, email, password, password_confirmation });
        user.value = res.data.user;
        // Session regenerate saat register membuat CSRF token baru — update meta tag
        await refreshCsrfToken();
    }

    async function logout() {
        await axios.post('/logout');
        user.value = null;
        // Reset nav items agar dimuat ulang saat login berikutnya
        useNavItems().resetNavItems();
        // Session invalidate saat logout membuat CSRF token baru — update meta tag
        await refreshCsrfToken();
    }

    const isAuthenticated = computed(() => !!user.value);

    function hasRole(role: string) {
        return user.value?.roles?.some((r: any) => r.name === role) ?? false;
    }

    return { user, initialized, isAuthenticated, fetchUser, login, register, logout, hasRole };
}
