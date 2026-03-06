<template>
    <div class="max-w-4xl mx-auto py-8 w-full">
        <h1 class="text-3xl font-bold mb-6 text-gray-800">Dashboard</h1>
        <div class="bg-white rounded-lg shadow p-6">
            <template v-if="hasRole('developer')">
                <p class="text-gray-700">Selamat datang, Developer! Anda dapat mengakses fitur pengembangan, monitoring, dan tools internal.</p>
            </template>
            <template v-else-if="hasRole('superAdmin')">
                <p class="text-gray-700">Selamat datang, Super Admin! Anda dapat mengelola seluruh sistem, user, dan event.</p>
            </template>
            <template v-else-if="hasRole('admin')">
                <p class="text-gray-700">Selamat datang, Admin! Anda dapat mengelola event dan tiket.</p>
            </template>
            <template v-else>
                <p class="text-gray-700">Selamat datang! Lihat event yang tersedia di halaman Jelajah.</p>
            </template>
        </div>
    </div>
</template>

<script setup lang="ts">
import { onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { useAuth } from '@/composables/useAuth';

const { isAuthenticated, fetchUser, hasRole } = useAuth();
const router = useRouter();

onMounted(async () => {
    await fetchUser();
    if (!isAuthenticated.value) {
        router.push('/');
    }
});
</script>
