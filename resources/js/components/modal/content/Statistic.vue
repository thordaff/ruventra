<script setup lang="ts">
import { CalendarDays, MapPin, Ticket, Users } from 'lucide-vue-next';
import { formatDate, formatRp } from '@/lib/format';
import type { Event } from '@/types/event';

defineProps<{ event: Event }>();
</script>

<template>
    <div class="mt-4 space-y-5">
        <!-- Info grid -->
        <div class="grid grid-cols-1 gap-3 sm:grid-cols-3">
            <div class="flex items-start gap-2 rounded-lg bg-gray-50 p-3">
                <CalendarDays class="mt-0.5 h-4 w-4 shrink-0 text-indigo-500" />
                <div>
                    <p class="text-xs text-gray-400">Tanggal</p>
                    <p class="text-sm font-medium">{{ formatDate(event.date) }}</p>
                </div>
            </div>
            <div class="flex items-start gap-2 rounded-lg bg-gray-50 p-3 sm:col-span-2">
                <MapPin class="mt-0.5 h-4 w-4 shrink-0 text-indigo-500" />
                <div>
                    <p class="text-xs text-gray-400">Lokasi</p>
                    <p class="text-sm font-medium">{{ event.location }}</p>
                </div>
            </div>
        </div>

        <!-- Stat grid -->
        <div class="grid grid-cols-3 gap-3 text-center">
            <div class="rounded-lg border bg-white p-3">
                <Ticket class="mx-auto mb-1 h-4 w-4 text-indigo-400" />
                <p class="text-xs text-gray-400">Tiket Terjual</p>
                <p class="font-bold text-indigo-600">{{ event.ticketsSold.toLocaleString('id-ID') }}</p>
            </div>
            <div class="rounded-lg border bg-white p-3">
                <Users class="mx-auto mb-1 h-4 w-4 text-sky-400" />
                <p class="text-xs text-gray-400">Kapasitas</p>
                <p class="font-bold text-sky-600">{{ event.capacity.toLocaleString('id-ID') }}</p>
            </div>
            <div class="rounded-lg border bg-white p-3">
                <p class="mb-1 text-xs text-gray-400">Revenue</p>
                <p class="text-sm font-bold text-green-600">{{ formatRp(event.revenue) }}</p>
            </div>
        </div>

        <!-- Line Up / Roster -->
        <div>
            <h3 class="mb-3 font-semibold text-gray-800">Line Up / Roster</h3>
            <div class="grid grid-cols-2 gap-2 sm:grid-cols-3">
                <div
                    v-for="artist in event.lineup"
                    :key="artist.name"
                    class="flex items-center gap-2.5 rounded-lg border bg-white p-3"
                >
                    <div
                        class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-indigo-100 text-sm font-bold text-indigo-700"
                    >
                        {{ artist.name.charAt(0) }}
                    </div>
                    <div>
                        <p class="text-sm font-medium leading-tight">{{ artist.name }}</p>
                        <p class="text-xs text-gray-400">{{ artist.role }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
