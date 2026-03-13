<script setup lang="ts">
import {
    ArcElement,
    BarElement,
    CategoryScale,
    Chart as ChartJS,
    Legend,
    LinearScale,
    LineElement,
    PointElement,
    Title,
    Tooltip,
} from 'chart.js';
import { ref } from 'vue';
import { Bar, Line } from 'vue-chartjs';

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale, ArcElement, LineElement, PointElement);

const revenueTable = ref([
    { month: 'Januari', events: 4, tickets: 480, gross: 48000000, net: 43200000 },
    { month: 'Februari', events: 6, tickets: 720, gross: 72000000, net: 64800000 },
    { month: 'Maret', events: 5, tickets: 610, gross: 61000000, net: 54900000 },
    { month: 'April', events: 8, tickets: 940, gross: 94000000, net: 84600000 },
    { month: 'Mei', events: 10, tickets: 1200, gross: 120000000, net: 108000000 },
    { month: 'Juni', events: 7, tickets: 830, gross: 83000000, net: 74700000 },
]);

const totalGross = revenueTable.value.reduce((s, r) => s + r.gross, 0);
const totalNet = revenueTable.value.reduce((s, r) => s + r.net, 0);
const totalTickets = revenueTable.value.reduce((s, r) => s + r.tickets, 0);
const totalEvents = revenueTable.value.reduce((s, r) => s + r.events, 0);

const months = revenueTable.value.map((r) => r.month);

const barData = {
    labels: months,
    datasets: [
        { label: 'Pendapatan Kotor', backgroundColor: '#6366f1', data: revenueTable.value.map((r) => r.gross / 1_000_000) },
        { label: 'Pendapatan Bersih', backgroundColor: '#a5b4fc', data: revenueTable.value.map((r) => r.net / 1_000_000) },
    ],
};

const lineData = {
    labels: months,
    datasets: [
        {
            label: 'Tiket Terjual',
            borderColor: '#6366f1',
            backgroundColor: 'rgba(99,102,241,0.1)',
            data: revenueTable.value.map((r) => r.tickets),
            fill: true,
            tension: 0.4,
        },
    ],
};

const barOptions = { responsive: true, plugins: { legend: { position: 'top' as const } } };
const lineOptions = { responsive: true, plugins: { legend: { display: false } } };

import { formatRp } from '@/lib/format';
</script>

<template>
    <div class="space-y-8">
        <h1 class="text-2xl font-bold">Revenue</h1>

        <!-- Summary Cards -->
        <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
            <div class="rounded-xl border bg-white p-5 shadow-sm">
                <p class="text-sm text-gray-500">Total Event</p>
                <p class="mt-1 text-3xl font-bold text-indigo-600">{{ totalEvents }}</p>
            </div>
            <div class="rounded-xl border bg-white p-5 shadow-sm">
                <p class="text-sm text-gray-500">Tiket Terjual</p>
                <p class="mt-1 text-3xl font-bold text-indigo-600">{{ totalTickets.toLocaleString('id-ID') }}</p>
            </div>
            <div class="rounded-xl border bg-white p-5 shadow-sm">
                <p class="text-sm text-gray-500">Pendapatan Kotor</p>
                <p class="mt-1 text-2xl font-bold text-indigo-600">{{ formatRp(totalGross) }}</p>
            </div>
            <div class="rounded-xl border bg-white p-5 shadow-sm">
                <p class="text-sm text-gray-500">Pendapatan Bersih</p>
                <p class="mt-1 text-2xl font-bold text-green-600">{{ formatRp(totalNet) }}</p>
            </div>
        </div>

        <!-- Charts -->
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            <div class="rounded-xl border bg-white p-6 shadow-sm">
                <h2 class="mb-4 font-semibold text-gray-700">Perbandingan Pendapatan (juta Rp)</h2>
                <Bar :data="barData" :options="barOptions" />
            </div>
            <div class="rounded-xl border bg-white p-6 shadow-sm">
                <h2 class="mb-4 font-semibold text-gray-700">Tren Tiket Terjual</h2>
                <Line :data="lineData" :options="lineOptions" />
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto rounded-xl border bg-white shadow-sm">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="border-b bg-gray-50 text-left text-gray-600">
                        <th class="px-5 py-3">Bulan</th>
                        <th class="px-5 py-3">Event</th>
                        <th class="px-5 py-3">Tiket</th>
                        <th class="px-5 py-3">Pendapatan Kotor</th>
                        <th class="px-5 py-3">Pendapatan Bersih</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="row in revenueTable" :key="row.month" class="border-t hover:bg-gray-50">
                        <td class="px-5 py-3 font-medium">{{ row.month }}</td>
                        <td class="px-5 py-3">{{ row.events }}</td>
                        <td class="px-5 py-3">{{ row.tickets.toLocaleString('id-ID') }}</td>
                        <td class="px-5 py-3">Rp {{ row.gross.toLocaleString('id-ID') }}</td>
                        <td class="px-5 py-3 text-green-600">Rp {{ row.net.toLocaleString('id-ID') }}</td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr class="border-t bg-gray-50 font-semibold">
                        <td class="px-5 py-3">Total</td>
                        <td class="px-5 py-3">{{ totalEvents }}</td>
                        <td class="px-5 py-3">{{ totalTickets.toLocaleString('id-ID') }}</td>
                        <td class="px-5 py-3">Rp {{ totalGross.toLocaleString('id-ID') }}</td>
                        <td class="px-5 py-3 text-green-600">Rp {{ totalNet.toLocaleString('id-ID') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</template>
