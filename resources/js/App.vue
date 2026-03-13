<script setup lang="ts">
import ToastContainer from '@/components/ToastContainer.vue';
import DuplicateSessionModal from '@/components/DuplicateSessionModal.vue';
import { duplicateSessionData, useAuth } from '@/composables/useAuth';
import { useToast } from '@/composables/useToast';
import { useRouter } from 'vue-router';

const router = useRouter();
const { forceLogin } = useAuth();
const { toast } = useToast();

async function handleForceLogin() {
    await forceLogin();
    toast('Login berhasil. Sesi sebelumnya telah diakhiri.', 'success');
    router.push('/dashboard');
}
</script>

<template>
    <router-view />
    <ToastContainer />
    <DuplicateSessionModal
        :show="duplicateSessionData.show"
        :attempts="duplicateSessionData.attempts"
        :email="duplicateSessionData.email"
        @confirm="handleForceLogin"
        @cancel="duplicateSessionData.show = false"
    />
</template>

