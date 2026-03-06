<template>
    <nav class="bg-white px-8 py-4 flex justify-between items-center shadow">
        <div>
            <router-link to="/" class="font-bold text-lg text-gray-800">Ruventra</router-link>
        </div>
        <div>
            <template v-if="isAuthenticated">
                <button
                    @click="doLogout"
                    class="bg-red-600 hover:bg-red-700 text-white font-semibold px-4 py-2 rounded transition"
                >
                    Logout
                </button>
            </template>
            <template v-else>
                <button
                    @click="emit('open-auth')"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded transition"
                >
                    Login / Register
                </button>
            </template>
        </div>
    </nav>
</template>

<script setup lang="ts">
import { useRouter } from 'vue-router';
import { useAuth } from '@/composables/useAuth';

const emit = defineEmits(['open-auth']);
const router = useRouter();
const { isAuthenticated, logout } = useAuth();

async function doLogout() {
    await logout();
    router.push('/');
}
</script>
