export type EventMode = 'offline' | 'online';

export interface ExploreEvent {
    id: number;
    title: string;
    city: string;
    region: string;
    mode: EventMode;
    date: string;
    venue: string;
    attendees: number;
    thumbnail: string;
    organizer: string;
    category: string;
    priceLabel: string;
    summary: string;
    description: string;
}

export const CITY_CATALOG: Array<{ city: string; region: string }> = [
    { city: 'Jakarta', region: 'DKI Jakarta' },
    { city: 'Bandung', region: 'Jawa Barat' },
    { city: 'Surabaya', region: 'Jawa Timur' },
    { city: 'Yogyakarta', region: 'DI Yogyakarta' },
    { city: 'Semarang', region: 'Jawa Tengah' },
    { city: 'Denpasar', region: 'Bali' },
    { city: 'Medan', region: 'Sumatera Utara' },
    { city: 'Palembang', region: 'Sumatera Selatan' },
    { city: 'Makassar', region: 'Sulawesi Selatan' },
    { city: 'Balikpapan', region: 'Kalimantan Timur' },
    { city: 'Pontianak', region: 'Kalimantan Barat' },
    { city: 'Manado', region: 'Sulawesi Utara' },
];

const EVENT_THEMES = [
    'Festival Musik Kota',
    'Tech Community Meet',
    'Pekan Kuliner Nusantara',
    'Creative Expo',
    'Startup Networking Night',
    'Workshop Bisnis Lokal',
    'Fun Run Weekend',
    'Konferensi Digital Indonesia',
];

const EVENT_CATEGORIES = [
    'Musik',
    'Teknologi',
    'Kuliner',
    'Komunitas',
    'Olahraga',
    'Bisnis',
];

const VENUE_NAMES = [
    'Convention Hall',
    'Creative Hub',
    'City Park',
    'Cultural Center',
    'Town Ballroom',
    'Community Space',
];

const ORGANIZERS = [
    'Ruventra Live',
    'Nusantara Connect',
    'Arunika Event House',
    'Kreasi Kota Indonesia',
    'Orbit Community',
    'SatuRuang Experience',
];

export const TOTAL_EXPLORE_EVENTS = 2400;

export const EXPLORE_EVENTS: ExploreEvent[] = Array.from({ length: TOTAL_EXPLORE_EVENTS }, (_, index) => {
    const cityMeta = CITY_CATALOG[index % CITY_CATALOG.length];
    const theme = EVENT_THEMES[index % EVENT_THEMES.length];
    const category = EVENT_CATEGORIES[index % EVENT_CATEGORIES.length];
    const venue = VENUE_NAMES[index % VENUE_NAMES.length];
    const organizer = ORGANIZERS[index % ORGANIZERS.length];
    const mode: EventMode = index % 3 === 0 ? 'online' : 'offline';
    const month = String((index % 12) + 1).padStart(2, '0');
    const day = String((index % 28) + 1).padStart(2, '0');
    const batch = Math.floor(index / EVENT_THEMES.length) + 1;
    const attendees = 120 + ((index * 67) % 7600);
    const title = `${theme} ${cityMeta.city} #${batch}`;
    const venueLabel = mode === 'online' ? `Streaming langsung dari ${cityMeta.city}` : `${venue} ${cityMeta.city}`;
    const priceLabel = mode === 'online' ? (index % 5 === 0 ? 'Gratis' : 'Mulai Rp49.000') : `Mulai Rp${(79000 + ((index % 7) * 25000)).toLocaleString('id-ID')}`;

    return {
        id: index + 1,
        title,
        city: cityMeta.city,
        region: cityMeta.region,
        mode,
        date: `2026-${month}-${day}`,
        venue: venueLabel,
        attendees,
        thumbnail: `https://picsum.photos/seed/jelajah-event-${index + 1}/640/360`,
        organizer,
        category,
        priceLabel,
        summary: `${title} menghadirkan pengalaman ${category.toLowerCase()} yang dikurasi untuk audiens di ${cityMeta.city} dan sekitarnya.`,
        description: `${title} adalah event ${mode === 'online' ? 'hybrid streaming' : 'tatap muka'} yang dirancang oleh ${organizer}. Program ini menyatukan komunitas, kreator, dan pengunjung dari ${cityMeta.region} dalam sesi inspiratif, networking, serta pengalaman interaktif yang relevan dengan tema ${category.toLowerCase()}.`,
    };
});

export function formatExploreEventDate(value: string): string {
    return new Date(value).toLocaleDateString('id-ID', {
        day: 'numeric',
        month: 'long',
        year: 'numeric',
    });
}

export function getExploreEventModeLabel(mode: EventMode): string {
    return mode === 'offline' ? 'Offline' : 'Online';
}

export function getExploreEventModeBadgeClass(
    mode: EventMode,
    tone: 'solid' | 'soft' = 'solid',
): string {
    if (mode === 'offline') {
        return tone === 'solid'
            ? 'bg-sky-600 text-white'
            : 'bg-sky-100 text-sky-700';
    }

    return tone === 'solid'
        ? 'bg-teal-600 text-white'
        : 'bg-teal-100 text-teal-700';
}

export function getExploreEventById(id: number): ExploreEvent | undefined {
    return EXPLORE_EVENTS.find((event) => event.id === id);
}
