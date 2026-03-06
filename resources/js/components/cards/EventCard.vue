<script setup lang="ts">
import { CalendarDays, MapPin } from 'lucide-vue-next';
import { getCategoryColor, occupancyPercent } from '@/lib/event';
import { formatDate, formatRpShort } from '@/lib/format';
import type { Event } from '@/types/event';

defineProps<{ event: Event }>();
defineEmits<{ click: [event: Event] }>();
</script>

<template>
    <div
        class="group cursor-pointer overflow-hidden rounded-xl border bg-white shadow-sm transition hover:shadow-md"
        @click="$emit('click', event)"
    >
        <!-- Thumbnail -->
        <div class="relative h-40 overflow-hidden bg-gray-100">
            <img
                :src="event.thumbnail"
                :alt="event.title"
                class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105"
            />
            <span
                class="absolute right-3 top-3 rounded-full px-2.5 py-1 text-xs font-medium"
                :class="getCategoryColor(event.category)"
            >
                {{ event.category }}
            </span>
        </div>

        <!-- Card Body -->
        <div class="space-y-3 p-4">
            <h3 class="line-clamp-1 font-semibold text-gray-900">{{ event.title }}</h3>

            <div class="flex items-center gap-1.5 text-xs text-gray-500">
                <CalendarDays class="h-3.5 w-3.5 shrink-0" />
                {{ formatDate(event.date) }}
            </div>

            <div class="flex items-center gap-1.5 text-xs text-gray-500">
                <MapPin class="h-3.5 w-3.5 shrink-0" />
                <span class="line-clamp-1">{{ event.location }}</span>
            </div>

            <div class="grid grid-cols-2 gap-2 border-t pt-3 text-center">
                <div>
                    <p class="text-xs text-gray-400">Tiket Terjual</p>
                    <p class="font-semibold text-indigo-600">{{ event.ticketsSold.toLocaleString('id-ID') }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400">Revenue</p>
                    <p class="text-sm font-semibold text-green-600">{{ formatRpShort(event.revenue) }}</p>
                </div>
            </div>

            <!-- Occupancy bar -->
            <div>
                <div class="mb-1 flex justify-between text-xs text-gray-400">
                    <span>Kapasitas</span>
                    <span>{{ occupancyPercent(event.ticketsSold, event.capacity) }}%</span>
                </div>
                <div class="h-1.5 w-full rounded-full bg-gray-100">
                    <div
                        class="h-1.5 rounded-full bg-indigo-500 transition-all"
                        :style="{ width: occupancyPercent(event.ticketsSold, event.capacity) + '%' }"
                    />
                </div>
            </div>
        </div>
    </div>
</template>
