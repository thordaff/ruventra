<script setup lang="ts">
import { computed, ref, watch } from 'vue';
import EventCard from '@/components/cards/EventCard.vue';
import StatisticContent from '@/components/modal/content/Statistic.vue';
import ModalLayout from '@/components/modal/Layout.vue';
import SearchFilter from '@/components/SearchFilter.vue';
import { getCategoryColor } from '@/lib/event';
import { formatRpShort } from '@/lib/format';
import type { Event } from '@/types/event';

const ALL_EVENTS: Event[] = [
    {
        id: 1,
        title: 'Java Jazz Festival 2026',
        thumbnail: 'https://picsum.photos/seed/jazz/600/300',
        date: '2026-02-01',
        location: 'Jakarta Convention Center, Jakarta',
        ticketsSold: 1200,
        capacity: 1500,
        revenue: 180000000,
        category: 'Musik',
        description: 'Festival jazz terbesar di Asia Tenggara yang menghadirkan musisi lokal dan internasional dalam satu panggung megah.',
        lineup: [
            { name: 'Diana Krall', role: 'Headliner' },
            { name: "Maliq & D'essentials", role: 'Co-Headliner' },
            { name: 'Andien', role: 'Performer' },
            { name: 'Joey Alexander', role: 'Performer' },
        ],
    },
    {
        id: 2,
        title: 'Tech Summit 2026',
        thumbnail: 'https://picsum.photos/seed/tech/600/300',
        date: '2026-03-15',
        location: 'Bali Nusa Dua Convention Center, Bali',
        ticketsSold: 850,
        capacity: 1000,
        revenue: 127500000,
        category: 'Teknologi',
        description: 'Konferensi teknologi tahunan yang menghadirkan inovator, developer, dan pemimpin industri untuk berbagi ide terdepan.',
        lineup: [
            { name: 'Nadiem Makarim', role: 'Keynote Speaker' },
            { name: 'William Tanuwijaya', role: 'Speaker' },
            { name: 'Kevin Aluwi', role: 'Panelist' },
            { name: 'Achmad Zaky', role: 'Panelist' },
        ],
    },
    {
        id: 3,
        title: 'Ruventra Expo 2026',
        thumbnail: 'https://picsum.photos/seed/expo/600/300',
        date: '2026-04-10',
        location: 'Surabaya Exhibition Center, Surabaya',
        ticketsSold: 640,
        capacity: 800,
        revenue: 64000000,
        category: 'Pameran',
        description: 'Pameran bisnis dan hiburan yang menampilkan ratusan brand lokal, pertunjukan seni, dan workshop kreatif.',
        lineup: [
            { name: 'Kangen Band', role: 'Performer' },
            { name: 'RAN', role: 'Performer' },
            { name: 'Rizky Febian', role: 'Performer' },
        ],
    },
    {
        id: 4,
        title: 'Food & Culture Fest',
        thumbnail: 'https://picsum.photos/seed/food/600/300',
        date: '2026-04-20',
        location: 'Lapangan Banteng, Jakarta',
        ticketsSold: 480,
        capacity: 600,
        revenue: 48000000,
        category: 'Kuliner',
        description: 'Festival kuliner dan budaya yang merayakan keberagaman cita rasa Indonesia dengan 200+ vendor makanan tradisional.',
        lineup: [
            { name: 'Chef Renata', role: 'Guest Chef' },
            { name: 'Chef Degan', role: 'Guest Chef' },
            { name: 'Farel Prayoga', role: 'Performer' },
        ],
    },
    {
        id: 5,
        title: 'Soundrenaline 2026',
        thumbnail: 'https://picsum.photos/seed/rock/600/300',
        date: '2026-05-08',
        location: 'Stadion Gelora Bung Karno, Jakarta',
        ticketsSold: 2100,
        capacity: 2500,
        revenue: 420000000,
        category: 'Musik',
        description: 'Festival musik rock dan metal terbesar Indonesia yang telah berdiri lebih dari dua dekade.',
        lineup: [
            { name: 'Slipknot', role: 'Headliner' },
            { name: 'Burgerkill', role: 'Co-Headliner' },
            { name: 'Superman Is Dead', role: 'Performer' },
            { name: 'Seringai', role: 'Performer' },
        ],
    },
    {
        id: 6,
        title: 'Bandung Digital Creative',
        thumbnail: 'https://picsum.photos/seed/digital/600/300',
        date: '2026-05-22',
        location: 'Trans Convention Center, Bandung',
        ticketsSold: 390,
        capacity: 500,
        revenue: 58500000,
        category: 'Teknologi',
        description: 'Expo industri kreatif digital yang menampilkan startup, seniman digital, dan konten kreator terbaik dari Jawa Barat.',
        lineup: [
            { name: 'Raditya Dika', role: 'Keynote' },
            { name: 'Rachel Vennya', role: 'Guest Speaker' },
        ],
    },
    {
        id: 7,
        title: 'Nusantara Culinary Week',
        thumbnail: 'https://picsum.photos/seed/culinary/600/300',
        date: '2026-06-05',
        location: 'Grand Ballroom Hotel Indonesia, Jakarta',
        ticketsSold: 310,
        capacity: 400,
        revenue: 62000000,
        category: 'Kuliner',
        description: 'Pekan kuliner premium yang menampilkan masakan nusantara dari 34 provinsi dengan chef berbintang internasional.',
        lineup: [
            { name: 'Chef Juna', role: 'Head Chef' },
            { name: 'Chef Arnold', role: 'Guest Chef' },
            { name: 'Chef Rinrin', role: 'Guest Chef' },
        ],
    },
    {
        id: 8,
        title: 'Indie Music Showcase',
        thumbnail: 'https://picsum.photos/seed/indie/600/300',
        date: '2026-06-18',
        location: 'Taman Ismail Marzuki, Jakarta',
        ticketsSold: 520,
        capacity: 700,
        revenue: 52000000,
        category: 'Musik',
        description: 'Panggung terbuka untuk musisi indie lokal berbakat yang ingin memperkenalkan karya mereka kepada publik lebih luas.',
        lineup: [
            { name: 'Hindia', role: 'Headliner' },
            { name: 'Feast', role: 'Performer' },
            { name: 'Pamungkas', role: 'Performer' },
            { name: 'Weird Genius', role: 'Co-Headliner' },
        ],
    },
    {
        id: 9,
        title: 'Indonesia Startup Week',
        thumbnail: 'https://picsum.photos/seed/startup/600/300',
        date: '2026-07-12',
        location: 'ICE BSD, Tangerang',
        ticketsSold: 1050,
        capacity: 1200,
        revenue: 157500000,
        category: 'Teknologi',
        description: 'Ajang pertemuan tahunan ekosistem startup Indonesia dengan investor, mentor, dan pelaku industri nasional.',
        lineup: [
            { name: 'Andi Boediman', role: 'Keynote' },
            { name: 'Pandu Sjahrir', role: 'Speaker' },
            { name: 'Shinta Dhanuwardoyo', role: 'Panelist' },
        ],
    },
    {
        id: 10,
        title: 'Batik & Craft Fair 2026',
        thumbnail: 'https://picsum.photos/seed/batik/600/300',
        date: '2026-07-28',
        location: 'Jogja Expo Center, Yogyakarta',
        ticketsSold: 720,
        capacity: 900,
        revenue: 72000000,
        category: 'Pameran',
        description: 'Pameran kerajinan dan batik nusantara terbesar yang menampilkan karya pengrajin lokal dari seluruh pelosok Indonesia.',
        lineup: [
            { name: 'Didik Nini Thowok', role: 'Penampil Seni' },
            { name: 'Sanggar Sonobudoyo', role: 'Pertunjukan Budaya' },
        ],
    },
    {
        id: 11,
        title: 'DxD Electronic Night',
        thumbnail: 'https://picsum.photos/seed/edm/600/300',
        date: '2026-08-09',
        location: 'Ancol Beach City, Jakarta',
        ticketsSold: 1800,
        capacity: 2000,
        revenue: 360000000,
        category: 'Musik',
        description: 'Pesta elektronik terbesar di pantai utara Jakarta dengan DJ internasional dan instalasi cahaya spektakuler.',
        lineup: [
            { name: 'Martin Garrix', role: 'Headliner' },
            { name: 'Dipha Barus', role: 'Co-Headliner' },
            { name: 'DJ Yasmin', role: 'Opener' },
        ],
    },
    {
        id: 12,
        title: 'Sate & Rendang Festival',
        thumbnail: 'https://picsum.photos/seed/rendang/600/300',
        date: '2026-08-23',
        location: 'Alun-alun Kota Padang, Sumatera Barat',
        ticketsSold: 430,
        capacity: 600,
        revenue: 43000000,
        category: 'Kuliner',
        description: 'Festival merayakan dua ikon kuliner Indonesia yang menghadirkan lomba memasak, pameran rempah, dan pertunjukan seni minang.',
        lineup: [
            { name: 'Chef Fakhrudin', role: 'Juri Utama' },
            { name: 'Grup Randai Minang', role: 'Pertunjukan Seni' },
            { name: 'Ivo Virzal', role: 'Performer' },
        ],
    },
];

const CATEGORIES = ['Semua', 'Musik', 'Teknologi', 'Pameran', 'Kuliner'];
const PER_PAGE = 6;

const searchDebounced = ref('');
const activeCategory = ref(CATEGORIES[0]);
const currentPage = ref(1);
const selectedEvent = ref<Event | null>(null);
const dialogOpen = ref(false);

// Reset page when search or filter changes
watch(searchDebounced, () => { currentPage.value = 1; });
watch(activeCategory, () => { currentPage.value = 1; });

const filteredEvents = computed(() => {
    let result = ALL_EVENTS;
    if (activeCategory.value !== 'Semua') {
        result = result.filter((e) => e.category === activeCategory.value);
    }
    if (searchDebounced.value.trim()) {
        const q = searchDebounced.value.toLowerCase();
        result = result.filter((e) => e.title.toLowerCase().includes(q));
    }
    return result;
});

const totalPages = computed(() => Math.max(1, Math.ceil(filteredEvents.value.length / PER_PAGE)));

const pagedEvents = computed(() => {
    const start = (currentPage.value - 1) * PER_PAGE;
    return filteredEvents.value.slice(start, start + PER_PAGE);
});

function openDetail(event: Event) {
    selectedEvent.value = event;
    dialogOpen.value = true;
}
</script>

<template>
    <div class="space-y-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Statistik Event</h1>
            <p class="mt-1 text-sm text-gray-500">Ringkasan seluruh event  klik kartu untuk melihat detail.</p>
        </div>

        <!-- Summary bar -->
        <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
            <div class="rounded-xl border bg-white p-4 shadow-sm text-center">
                <p class="text-xs text-gray-500">Total Event</p>
                <p class="text-2xl font-bold text-indigo-600">{{ ALL_EVENTS.length }}</p>
            </div>
            <div class="rounded-xl border bg-white p-4 shadow-sm text-center">
                <p class="text-xs text-gray-500">Total Tiket Terjual</p>
                <p class="text-2xl font-bold text-indigo-600">{{ ALL_EVENTS.reduce((s,e)=>s+e.ticketsSold,0).toLocaleString('id-ID') }}</p>
            </div>
            <div class="rounded-xl border bg-white p-4 shadow-sm text-center">
                <p class="text-xs text-gray-500">Total Peserta</p>
                <p class="text-2xl font-bold text-indigo-600">{{ ALL_EVENTS.reduce((s,e)=>s+e.capacity,0).toLocaleString('id-ID') }}</p>
            </div>
            <div class="rounded-xl border bg-white p-4 shadow-sm text-center">
                <p class="text-xs text-gray-500">Total Revenue</p>
                <p class="text-xl font-bold text-green-600">{{ formatRpShort(ALL_EVENTS.reduce((s, e) => s + e.revenue, 0)) }}</p>
            </div>
        </div>

        <!-- Search & Filter -->
        <SearchFilter
            v-model:search="searchDebounced"
            v-model:filter="activeCategory"
            :filters="CATEGORIES"
            placeholder="Cari nama event…"
        />

        <!-- Result count -->
        <p class="text-xs text-gray-400">
            Menampilkan {{ pagedEvents.length }} dari {{ filteredEvents.length }} event
            <template v-if="activeCategory !== CATEGORIES[0]"> · Kategori: <strong>{{ activeCategory }}</strong></template>
            <template v-if="searchDebounced"> · Pencarian: "<strong>{{ searchDebounced }}</strong>"</template>
        </p>

        <!-- Event Cards Grid -->
        <div v-if="pagedEvents.length" class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
            <EventCard
                v-for="event in pagedEvents"
                :key="event.id"
                :event="event"
                @click="openDetail"
            />
        </div>

        <!-- Empty state -->
        <div v-else class="rounded-xl border bg-white py-16 text-center text-gray-400 shadow-sm">
            <Search class="mx-auto mb-3 h-10 w-10 opacity-30" />
            <p class="font-medium">Tidak ada event ditemukan.</p>
            <p class="mt-1 text-sm">Coba ubah kata kunci atau kategori filter.</p>
        </div>

        <!-- Pagination -->
        <div v-if="totalPages > 1" class="flex items-center justify-center gap-1">
            <button
                type="button"
                class="rounded-lg border px-3 py-1.5 text-sm transition disabled:opacity-40"
                :disabled="currentPage === 1"
                @click="currentPage--"
            >
                Prev
            </button>
            <button
                v-for="page in totalPages"
                :key="page"
                type="button"
                class="rounded-lg border px-3 py-1.5 text-sm transition"
                :class="page === currentPage
                    ? 'border-indigo-500 bg-indigo-500 text-white'
                    : 'border-gray-200 bg-white text-gray-600 hover:border-indigo-300'"
                @click="currentPage = page"
            >
                {{ page }}
            </button>
            <button
                type="button"
                class="rounded-lg border px-3 py-1.5 text-sm transition disabled:opacity-40"
                :disabled="currentPage === totalPages"
                @click="currentPage++"
            >
                Next
            </button>
        </div>

        <!-- Event Detail Modal -->
        <template v-if="selectedEvent">
            <ModalLayout
                v-model:open="dialogOpen"
                :open="dialogOpen"
                :thumbnail="selectedEvent.thumbnail"
                :title="selectedEvent.title"
                :category="selectedEvent.category"
                :category-color-class="getCategoryColor(selectedEvent.category)"
                :description="selectedEvent.description"
                @update:open="dialogOpen = $event"
            >
                <StatisticContent :event="selectedEvent" />
            </ModalLayout>
        </template>
    </div>
</template>

