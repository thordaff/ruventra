<template>
    <div class="flex flex-col items-center justify-center min-h-full py-20 text-center">
        <h1 class="text-9xl font-extrabold text-blue-600 mb-4">404</h1>
        <h2 class="text-2xl font-bold text-gray-800 mb-2">Halaman Tidak Ditemukan</h2>
        <p class="text-gray-500 mb-8">Halaman yang Anda cari tidak ada atau sudah dipindahkan.</p>
        <router-link
            to="/"
            class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded transition"
        >
            Kembali ke Beranda
        </router-link>
    </div>
</template>

<script setup lang="ts">
// 404 Not Found page
</script>
