<script setup lang="ts">
import { Search } from 'lucide-vue-next';
import { Input } from '@/components/ui/input';

defineProps<{
    cityFilter: string;
    cityOptions: string[];
    searchValue: string;
    showOffline: boolean;
    showOnline: boolean;
}>();

const emit = defineEmits<{
    'toggle:offline': [];
    'toggle:online': [];
    'update:cityFilter': [value: string];
    'update:searchValue': [value: string];
}>();
</script>

<template>
    <div class="rounded-2xl border border-slate-200 bg-white/90 p-4 shadow-lg shadow-slate-200/60 backdrop-blur-sm">
        <div class="space-y-4">
            <div>
                <label for="event-search" class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500">
                    Cari event / wilayah
                </label>
                <div class="relative">
                    <Search class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" />
                    <Input
                        id="event-search"
                        :model-value="searchValue"
                        placeholder="Contoh: Bandung, startup, Jawa Timur"
                        class="h-10 pl-10"
                        @update:model-value="emit('update:searchValue', String($event))"
                    />
                </div>
            </div>

            <div>
                <p class="mb-2 text-xs font-semibold uppercase tracking-wide text-slate-500">Mode event</p>
                <div class="grid grid-cols-2 gap-2">
                    <button
                        type="button"
                        class="rounded-lg border px-3 py-2 text-sm font-medium transition"
                        :class="showOffline
                            ? 'border-sky-600 bg-sky-600 text-white'
                            : 'border-slate-200 bg-white text-slate-700 hover:border-sky-300'"
                        :aria-pressed="showOffline"
                        @click="emit('toggle:offline')"
                    >
                        Offline
                    </button>
                    <button
                        type="button"
                        class="rounded-lg border px-3 py-2 text-sm font-medium transition"
                        :class="showOnline
                            ? 'border-teal-600 bg-teal-600 text-white'
                            : 'border-slate-200 bg-white text-slate-700 hover:border-teal-300'"
                        :aria-pressed="showOnline"
                        @click="emit('toggle:online')"
                    >
                        Online
                    </button>
                </div>
                <p class="mt-2 text-xs text-slate-400">Minimal satu mode tetap aktif.</p>
            </div>

            <div>
                <p class="mb-2 text-xs font-semibold uppercase tracking-wide text-slate-500">Kota</p>
                <div class="max-h-72 space-y-1.5 overflow-y-auto pr-1">
                    <button
                        v-for="city in cityOptions"
                        :key="city"
                        type="button"
                        class="w-full rounded-lg border px-3 py-2 text-left text-sm transition"
                        :class="cityFilter === city
                            ? 'border-slate-900 bg-slate-900 text-white'
                            : 'border-slate-200 bg-white text-slate-700 hover:border-slate-300'"
                        @click="emit('update:cityFilter', city)"
                    >
                        {{ city }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>