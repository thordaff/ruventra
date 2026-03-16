<script setup lang="ts">
import {
    ArrowRight,
    CalendarDays,
    MapPin,
    Users,
} from 'lucide-vue-next';
import {
    formatExploreEventDate,
    getExploreEventModeBadgeClass,
    getExploreEventModeLabel,
} from '@/lib/explore-events';
import type { ExploreEvent } from '@/lib/explore-events';

const props = defineProps<{
    event: ExploreEvent;
}>();

const emit = defineEmits<{
    click: [event: ExploreEvent];
}>();
</script>

<template>
    <button
        type="button"
        class="group flex h-full flex-col overflow-hidden rounded-3xl border border-slate-200 bg-white text-left shadow-sm transition hover:-translate-y-1 hover:border-sky-200 hover:shadow-lg focus-visible:border-sky-500 focus-visible:ring-2 focus-visible:ring-sky-200 focus-visible:outline-none"
        @click="emit('click', props.event)"
    >
        <div class="relative h-44 overflow-hidden bg-slate-100">
            <img
                :src="props.event.thumbnail"
                :alt="props.event.title"
                class="h-full w-full object-cover transition duration-300 group-hover:scale-105"
            />
            <div class="absolute inset-x-0 top-0 flex items-start justify-between gap-2 p-3">
                <span
                    class="rounded-full px-2.5 py-1 text-xs font-medium"
                    :class="getExploreEventModeBadgeClass(props.event.mode)"
                >
                    {{ getExploreEventModeLabel(props.event.mode) }}
                </span>
                <span class="rounded-full bg-white/90 px-2.5 py-1 text-xs font-semibold text-slate-800">
                    {{ props.event.priceLabel }}
                </span>
            </div>
        </div>

        <div class="flex flex-1 flex-col p-4">
            <div class="mb-2 flex flex-wrap items-center gap-2 text-xs text-slate-500">
                <span class="rounded-full bg-slate-100 px-2 py-1 font-medium text-slate-700">
                    {{ props.event.category }}
                </span>
                <span>{{ props.event.city }}, {{ props.event.region }}</span>
            </div>

            <h2 class="line-clamp-2 text-lg font-semibold leading-snug text-slate-900">
                {{ props.event.title }}
            </h2>

            <p class="mt-2 line-clamp-2 text-sm leading-6 text-slate-600">
                {{ props.event.summary }}
            </p>

            <div class="mt-4 space-y-2 text-sm text-slate-500">
                <p class="inline-flex items-center gap-2">
                    <CalendarDays class="size-4 text-sky-600" />
                    {{ formatExploreEventDate(props.event.date) }}
                </p>
                <p class="inline-flex items-center gap-2">
                    <MapPin class="size-4 text-sky-600" />
                    {{ props.event.venue }}
                </p>
            </div>

            <div class="mt-auto flex items-center justify-between pt-4 text-sm">
                <span class="inline-flex items-center gap-2 text-slate-500">
                    <Users class="size-4 text-sky-600" />
                    {{ props.event.attendees.toLocaleString('id-ID') }} peserta
                </span>
                <span class="inline-flex items-center gap-1 font-semibold text-sky-700">
                    Preview event
                    <ArrowRight class="size-4 transition group-hover:translate-x-1" />
                </span>
            </div>
        </div>
    </button>
</template>