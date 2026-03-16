<script setup lang="ts">
import {
    ArrowLeft,
    Ticket,
} from 'lucide-vue-next';
import { computed } from 'vue';
import { RouterLink, useRoute } from 'vue-router';
import ExploreEventOverviewGrid from '@/components/explore-events/ExploreEventOverviewGrid.vue';
import { Button } from '@/components/ui/button';
import {
    getExploreEventById,
    getExploreEventModeBadgeClass,
    getExploreEventModeLabel,
} from '@/lib/explore-events';

const route = useRoute();

const eventId = computed(() => Number(route.params.id));
const event = computed(() => {
    if (!Number.isInteger(eventId.value)) {
        return undefined;
    }

    return getExploreEventById(eventId.value);
});
</script>

<template>
    <div class="mx-auto w-full max-w-6xl px-3 sm:px-4 lg:px-6">
        <template v-if="event">
            <div class="mb-5 flex items-center justify-between gap-3">
                <Button as-child variant="outline">
                    <RouterLink to="/jelajah-event">
                        <ArrowLeft class="size-4" />
                        Kembali ke jelajah event
                    </RouterLink>
                </Button>
            </div>

            <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
                <div class="grid gap-0 lg:grid-cols-[1.2fr_minmax(0,0.8fr)]">
                    <div class="relative min-h-80 overflow-hidden bg-slate-100">
                        <img
                            :src="event.thumbnail"
                            :alt="event.title"
                            class="h-full w-full object-cover"
                        />
                        <div class="absolute left-5 top-5 flex flex-wrap gap-2 text-xs font-medium">
                            <span class="rounded-full bg-white/90 px-3 py-1 text-slate-800">
                                {{ event.category }}
                            </span>
                            <span
                                class="rounded-full px-3 py-1"
                                :class="getExploreEventModeBadgeClass(event.mode)"
                            >
                                {{ getExploreEventModeLabel(event.mode) }}
                            </span>
                        </div>
                    </div>

                    <div class="space-y-6 p-6 lg:p-8">
                        <div>
                            <p class="text-sm font-medium text-slate-500">Diselenggarakan oleh {{ event.organizer }}</p>
                            <h1 class="mt-2 text-3xl font-bold leading-tight text-slate-900">
                                {{ event.title }}
                            </h1>
                            <p class="mt-3 text-sm leading-6 text-slate-600">
                                {{ event.summary }}
                            </p>
                        </div>

                        <ExploreEventOverviewGrid :event="event" variant="detail" />

                        <div class="rounded-2xl border border-emerald-100 bg-emerald-50 p-4 text-sm text-emerald-800">
                            <p class="font-semibold">Harga tiket</p>
                            <p class="mt-1 inline-flex items-center gap-2">
                                <Ticket class="size-4" />
                                {{ event.priceLabel }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-6 grid gap-6 lg:grid-cols-[minmax(0,1fr)_320px]">
                <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="text-xl font-bold text-slate-900">Tentang event</h2>
                    <p class="mt-4 text-sm leading-7 text-slate-600">
                        {{ event.description }}
                    </p>
                    <p class="mt-4 text-sm leading-7 text-slate-600">
                        Pengunjung akan mendapatkan sesi utama, area networking, dan pengalaman yang disesuaikan dengan karakter audiens di {{ event.city }}. Halaman ini bisa Anda sambungkan ke data backend kapan saja tanpa perlu mengubah pola navigasi dari popup preview.
                    </p>
                </section>

                <aside class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="text-lg font-bold text-slate-900">Ringkasan cepat</h2>
                    <div class="mt-4 space-y-4 text-sm text-slate-600">
                        <div>
                            <p class="text-xs uppercase tracking-wide text-slate-500">Kategori</p>
                            <p class="mt-1 font-semibold text-slate-900">{{ event.category }}</p>
                        </div>
                        <div>
                            <p class="text-xs uppercase tracking-wide text-slate-500">Mode</p>
                            <p class="mt-1 font-semibold text-slate-900">{{ getExploreEventModeLabel(event.mode) }}</p>
                        </div>
                        <div>
                            <p class="text-xs uppercase tracking-wide text-slate-500">Organizer</p>
                            <p class="mt-1 font-semibold text-slate-900">{{ event.organizer }}</p>
                        </div>
                    </div>
                </aside>
            </div>
        </template>

        <div v-else class="rounded-3xl border border-slate-200 bg-white p-10 text-center shadow-sm">
            <h1 class="text-2xl font-bold text-slate-900">Event tidak ditemukan</h1>
            <p class="mt-3 text-sm text-slate-600">
                ID event yang dibuka tidak tersedia atau belum dimuat.
            </p>
            <div class="mt-6">
                <Button as-child>
                    <RouterLink to="/jelajah-event">Kembali ke daftar event</RouterLink>
                </Button>
            </div>
        </div>
    </div>
</template>
