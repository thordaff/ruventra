<script setup lang="ts">
import { AlertTriangle, LogIn, X } from 'lucide-vue-next';

defineProps<{
    show: boolean;
    attempts: number;
    email: string;
}>();

const emit = defineEmits<{
    (e: 'confirm'): void;
    (e: 'cancel'): void;
}>();
</script>

<template>
    <Teleport to="body">
        <Transition
            enter-active-class="transition duration-200 ease-out"
            enter-from-class="opacity-0 scale-95"
            enter-to-class="opacity-100 scale-100"
            leave-active-class="transition duration-150 ease-in"
            leave-from-class="opacity-100 scale-100"
            leave-to-class="opacity-0 scale-95"
        >
            <div
                v-if="show"
                class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/50 p-4"
                @click.self="emit('cancel')"
            >
                <div class="relative w-full max-w-md overflow-hidden rounded-2xl bg-white shadow-2xl ring-1 ring-black/5">
                    <!-- Red top stripe for 3+ attempts -->
                    <div
                        :class="attempts >= 3 ? 'bg-red-600' : 'bg-yellow-500'"
                        class="absolute inset-x-0 top-0 h-1"
                    />

                    <!-- Close -->
                    <button
                        class="absolute right-4 top-4 text-gray-400 hover:text-gray-700"
                        @click="emit('cancel')"
                    >
                        <X class="h-5 w-5" />
                    </button>

                    <div class="px-6 pt-8 pb-6">
                        <!-- Icon -->
                        <div
                            :class="attempts >= 3 ? 'bg-red-100 text-red-600' : 'bg-yellow-100 text-yellow-600'"
                            class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full"
                        >
                            <AlertTriangle class="h-7 w-7" />
                        </div>

                        <!-- Title -->
                        <h2 class="text-center text-lg font-bold text-gray-900">
                            {{ attempts >= 3 ? 'Percobaan Login Mencurigakan!' : 'Akun Sudah Aktif di Tempat Lain' }}
                        </h2>

                        <!-- Body -->
                        <p class="mt-2 text-center text-sm text-gray-600">
                            Akun <span class="font-semibold text-gray-800">{{ email }}</span> saat ini sudah
                            aktif di perangkat atau browser lain.
                        </p>

                        <!-- Critical warning -->
                        <div
                            v-if="attempts >= 3"
                            class="mt-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700"
                        >
                            <span class="font-semibold">Peringatan:</span> Ini adalah percobaan login ganda
                            ke-{{ attempts }}. Aktivitas ini telah dicatat sebagai
                            <span class="font-semibold">CRITICAL</span> di System Logs.
                        </div>

                        <p v-if="attempts < 3" class="mt-3 text-center text-xs text-gray-400">
                            Percobaan ke-{{ attempts }} — login paksa akan mengakhiri sesi yang aktif.
                        </p>

                        <!-- Actions -->
                        <div class="mt-6 flex flex-col gap-3">
                            <button
                                :class="attempts >= 3
                                    ? 'bg-red-600 hover:bg-red-700'
                                    : 'bg-indigo-600 hover:bg-indigo-700'"
                                class="flex items-center justify-center gap-2 rounded-lg py-2.5 text-sm font-semibold text-white transition"
                                @click="emit('confirm')"
                            >
                                <LogIn class="h-4 w-4" />
                                {{ attempts >= 3 ? 'Paksa Login Sekarang' : 'Lanjutkan & Akhiri Sesi Lain' }}
                            </button>
                            <button
                                class="rounded-lg py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-50"
                                @click="emit('cancel')"
                            >
                                Batal
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>
