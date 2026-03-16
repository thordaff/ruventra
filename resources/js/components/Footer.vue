<template>
    <footer class="w-full bg-gray-900 text-gray-200 pt-10 pb-6 px-4 mt-12">
        <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-4 gap-8">
            <div>
                <h3 class="font-bold text-lg mb-2">Ruventra</h3>
                <p class="text-sm mb-4">
                    Rumah Event Nusantara, platform penyedia event dan penjualan tiket online.
                </p>
                <span class="text-xs">&copy; {{ year }} Ruventra</span>
            </div>
            <div>
                <h4 class="font-semibold mb-2">Navigasi</h4>
                <ul class="space-y-1 text-sm">
                    <li>
                        <router-link to="/jelajah-event" class="hover:text-blue-400">Jelajah Event</router-link>
                    </li>
                    <li>
                        <router-link to="/dashboard" class="hover:text-blue-400">Dashboard</router-link>
                    </li>
                </ul>
            </div>
            <div>
                <h4 class="font-semibold mb-2">Penjualan Tiket</h4>
                <ul class="space-y-1 text-sm">
                    <li><a href="#" class="hover:text-blue-400">Cara Beli Tiket</a></li>
                    <li><a href="#" class="hover:text-blue-400">Syarat &amp; Ketentuan</a></li>
                    <li><a href="#" class="hover:text-blue-400">FAQ</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-semibold mb-2">Kontak &amp; Sosial</h4>
                <ul class="space-y-1 text-sm">
                    <li>
                        Email:
                        <a href="mailto:info@ruventra.com" class="hover:text-blue-400"
                            >info@ruventra.com</a
                        >
                    </li>
                    <li>Instagram: <a href="#" class="hover:text-blue-400">@ruventra</a></li>
                    <li>WhatsApp: <a href="#" class="hover:text-blue-400">0812-3456-7890</a></li>
                </ul>
            </div>
        </div>
    </footer>
</template>

<script setup lang="ts">
const year = new Date().getFullYear();
</script>
