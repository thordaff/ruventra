import axios from 'axios';
import { computed, ref } from 'vue';

const user = ref<Record<string, any> | null>(null);
const initialized = ref(false);

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
    }

    async function register(
        name: string,
        email: string,
        password: string,
        password_confirmation: string,
    ) {
        const res = await axios.post('/register', { name, email, password, password_confirmation });
        user.value = res.data.user;
    }

    async function logout() {
        await axios.post('/logout');
        user.value = null;
    }

    const isAuthenticated = computed(() => !!user.value);

    function hasRole(role: string) {
        return user.value?.roles?.some((r: any) => r.name === role) ?? false;
    }

    return { user, initialized, isAuthenticated, fetchUser, login, register, logout, hasRole };
}
