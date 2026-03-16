<script setup lang="ts">
import {
    CalendarDays,
    MapPin,
    MonitorPlay,
    Ticket,
    Users,
} from 'lucide-vue-next';
import type { Component } from 'vue';
import { computed } from 'vue';
import { formatExploreEventDate } from '@/lib/explore-events';
import type { ExploreEvent } from '@/lib/explore-events';

interface OverviewItem {
    icon: Component;
    label: string;
    value: string;
}

const props = withDefaults(defineProps<{
    event: ExploreEvent;
    variant?: 'detail' | 'preview';
}>(), {
    variant: 'preview',
});

const items = computed<OverviewItem[]>(() => {
    if (props.variant === 'detail') {
        return [
            {
                icon: CalendarDays,
                label: 'Tanggal',
                value: formatExploreEventDate(props.event.date),
            },
            {
                icon: MapPin,
                label: 'Lokasi',
                value: `${props.event.city}, ${props.event.region}`,
            },
            {
                icon: MonitorPlay,
                label: 'Akses',
                value: props.event.venue,
            },
            {
                icon: Users,
                label: 'Peserta',
                value: `${props.event.attendees.toLocaleString('id-ID')} peserta`,
            },
        ];
    }

    return [
        {
            icon: CalendarDays,
            label: 'Tanggal',
            value: formatExploreEventDate(props.event.date),
        },
        {
            icon: MapPin,
            label: 'Lokasi',
            value: `${props.event.city}, ${props.event.region}`,
        },
        {
            icon: MonitorPlay,
            label: 'Venue / akses',
            value: props.event.venue,
        },
        {
            icon: Ticket,
            label: 'Harga tiket',
            value: props.event.priceLabel,
        },
    ];
});
</script>

<template>
    <div class="grid gap-3 sm:grid-cols-2">
        <div
            v-for="item in items"
            :key="item.label"
            class="rounded-2xl border border-slate-200 bg-slate-50 p-4 text-sm text-slate-700"
        >
            <p class="text-xs uppercase tracking-wide text-slate-500">{{ item.label }}</p>
            <p class="mt-2 inline-flex items-center gap-2 font-semibold">
                <component :is="item.icon" class="size-4 text-sky-600" />
                {{ item.value }}
            </p>
        </div>
    </div>
</template>