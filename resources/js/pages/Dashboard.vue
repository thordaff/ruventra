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
import { CalendarDays, DollarSign, TicketCheck, TrendingUp, Users } from 'lucide-vue-next';
import { Bar, Pie } from 'vue-chartjs';
import { RouterLink } from 'vue-router';
import { useAuth } from '@/composables/useAuth';

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale, ArcElement, LineElement, PointElement);

useAuth();

const summaryCards = [
    { label: 'Total Event', value: '48', icon: CalendarDays, color: 'text-indigo-600', bg: 'bg-indigo-50' },
    { label: 'Tiket Terjual', value: '4.780', icon: TicketCheck, color: 'text-violet-600', bg: 'bg-violet-50' },
    { label: 'Peserta', value: '12.340', icon: Users, color: 'text-sky-600', bg: 'bg-sky-50' },
    { label: 'Pendapatan', value: 'Rp 478 jt', icon: DollarSign, color: 'text-green-600', bg: 'bg-green-50' },
];

const recentEvents = [
    { name: 'Java Jazz Festival', date: '2026-02-01', tickets: 1200, status: 'Selesai' },
    { name: 'Tech Summit 2026', date: '2026-03-15', tickets: 850, status: 'Berlangsung' },
    { name: 'Ruventra Expo', date: '2026-04-10', tickets: 640, status: 'Akan Datang' },
    { name: 'Food & Culture Fest', date: '2026-04-20', tickets: 480, status: 'Akan Datang' },
];

const barData = {
    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
    datasets: [
        {
            label: 'Tiket Terjual',
            backgroundColor: '#6366f1',
            data: [480, 720, 610, 940, 1200, 830],
        },
    ],
};

const pieData = {
    labels: ['Musik', 'Teknologi', 'Pameran', 'Kuliner'],
    datasets: [
        {
            backgroundColor: ['#6366f1', '#a5b4fc', '#818cf8', '#c7d2fe'],
            data: [35, 25, 20, 20],
        },
    ],
};

const barOptions = { responsive: true, plugins: { legend: { display: false } } };
const pieOptions = { responsive: true };

const statusClass: Record<string, string> = {
    'Selesai': 'bg-gray-100 text-gray-600',
    'Berlangsung': 'bg-green-100 text-green-700',
    'Akan Datang': 'bg-indigo-100 text-indigo-700',
};
</script>

<template>
    <div class="space-y-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
            <p class="mt-1 text-sm text-gray-500">Selamat datang di panel pengelola Ruventra.</p>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
            <div v-for="card in summaryCards" :key="card.label" class="flex items-center gap-4 rounded-xl border bg-white p-5 shadow-sm">
                <div :class="[card.bg, 'rounded-lg p-3']">
                    <component :is="card.icon" :class="[card.color, 'h-5 w-5']" />
                </div>
                <div>
                    <p class="text-xs text-gray-500">{{ card.label }}</p>
                    <p class="text-xl font-bold text-gray-800">{{ card.value }}</p>
                </div>
            </div>
        </div>

        <!-- Charts -->
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <!-- Bar Chart -->
            <div class="col-span-2 rounded-xl border bg-white p-6 shadow-sm">
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="font-semibold text-gray-700">Statistik Tiket (6 Bulan Terakhir)</h2>
                    <RouterLink to="/dashboard/statistics" class="flex items-center gap-1 text-xs text-indigo-600 hover:underline">
                        <TrendingUp class="h-3 w-3" /> Lihat detail
                    </RouterLink>
                </div>
                <Bar :data="barData" :options="barOptions" />
            </div>

            <!-- Pie Chart -->
            <div class="rounded-xl border bg-white p-6 shadow-sm">
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="font-semibold text-gray-700">Kategori Event</h2>
                    <RouterLink to="/dashboard/revenue" class="flex items-center gap-1 text-xs text-indigo-600 hover:underline">
                        <DollarSign class="h-3 w-3" /> Revenue
                    </RouterLink>
                </div>
                <Pie :data="pieData" :options="pieOptions" />
            </div>
        </div>

        <!-- Recent Events Table -->
        <div class="rounded-xl border bg-white shadow-sm">
            <div class="flex items-center justify-between border-b px-6 py-4">
                <h2 class="font-semibold text-gray-700">Event Terbaru</h2>
            </div>
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 text-left text-gray-500">
                        <th class="px-6 py-3">Nama Event</th>
                        <th class="px-6 py-3">Tanggal</th>
                        <th class="px-6 py-3">Tiket</th>
                        <th class="px-6 py-3">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="event in recentEvents" :key="event.name" class="border-t hover:bg-gray-50">
                        <td class="px-6 py-3 font-medium text-gray-800">{{ event.name }}</td>
                        <td class="px-6 py-3 text-gray-500">{{ event.date }}</td>
                        <td class="px-6 py-3">{{ event.tickets.toLocaleString('id-ID') }}</td>
                        <td class="px-6 py-3">
                            <span :class="[statusClass[event.status], 'rounded-full px-2.5 py-1 text-xs font-medium']">
                                {{ event.status }}
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

