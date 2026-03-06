<template>
    <div class="flex flex-col min-h-screen bg-gray-50">
        <Navbar @open-auth="showModal = true" />
        <main class="flex-1 flex flex-col py-8 px-2">
            <router-view />
        </main>
        <AuthModal :show="showModal" @close="showModal = false" />
        <Footer />
    </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import AuthModal from '@/components/AuthModal.vue';
import Footer from '@/components/Footer.vue';
import Navbar from '@/components/Navbar.vue';

const showModal = ref(false);
</script>
