import { computed, onBeforeUnmount, onMounted, ref, watch } from 'vue';
import { CITY_CATALOG, EXPLORE_EVENTS } from '@/lib/explore-events';
import type { ExploreEvent } from '@/lib/explore-events';

const DEFAULT_CITY_FILTER = 'Semua kota';
const ROW_HEIGHT = 408;
const ROW_GAP = 16;
const OVERSCAN_ROWS = 4;

export function useExploreEventBrowse() {
    const cityFilter = ref(DEFAULT_CITY_FILTER);
    const searchQuery = ref('');
    const showOffline = ref(true);
    const showOnline = ref(true);
    const selectedEvent = ref<ExploreEvent | null>(null);
    const previewOpen = ref(false);

    const listContainerRef = ref<HTMLElement | null>(null);
    const scrollTop = ref(0);
    const viewportHeight = ref(0);
    const containerWidth = ref(0);

    let resizeObserver: ResizeObserver | null = null;

    const cityOptions = computed(() => [
        DEFAULT_CITY_FILTER,
        ...CITY_CATALOG.map((item) => item.city),
    ]);

    const filteredEvents = computed(() => {
        const query = searchQuery.value.trim().toLowerCase();

        return EXPLORE_EVENTS.filter((event) => {
            const cityMatched =
                cityFilter.value === DEFAULT_CITY_FILTER || cityFilter.value === event.city;
            const modeMatched =
                (showOffline.value && event.mode === 'offline') ||
                (showOnline.value && event.mode === 'online');

            if (!cityMatched || !modeMatched) {
                return false;
            }

            if (!query) {
                return true;
            }

            const searchableText = [
                event.title,
                event.city,
                event.region,
                event.organizer,
                event.category,
            ]
                .join(' ')
                .toLowerCase();

            return searchableText.includes(query);
        });
    });

    const columnCount = computed(() => {
        if (containerWidth.value >= 1080) {
            return 3;
        }

        if (containerWidth.value >= 720) {
            return 2;
        }

        return 1;
    });

    const eventRows = computed(() => {
        const rows: ExploreEvent[][] = [];

        for (let index = 0; index < filteredEvents.value.length; index += columnCount.value) {
            rows.push(filteredEvents.value.slice(index, index + columnCount.value));
        }

        return rows;
    });

    const visibleStartRow = computed(() => {
        return Math.max(0, Math.floor(scrollTop.value / ROW_HEIGHT) - OVERSCAN_ROWS);
    });

    const visibleEndRow = computed(() => {
        const rawEnd =
            Math.ceil((scrollTop.value + viewportHeight.value) / ROW_HEIGHT) + OVERSCAN_ROWS;
        return Math.min(eventRows.value.length, rawEnd);
    });

    const visibleRows = computed(() => {
        return eventRows.value
            .slice(visibleStartRow.value, visibleEndRow.value)
            .map((events, localIndex) => ({
                events,
                rowIndex: visibleStartRow.value + localIndex,
            }));
    });

    const visibleCardCount = computed(() => {
        return visibleRows.value.reduce((count, row) => count + row.events.length, 0);
    });

    const topSpacerHeight = computed(() => visibleStartRow.value * ROW_HEIGHT);

    const bottomSpacerHeight = computed(() => {
        const hiddenRowCount = eventRows.value.length - visibleEndRow.value;
        return Math.max(0, hiddenRowCount * ROW_HEIGHT);
    });

    function syncListMetrics() {
        if (!listContainerRef.value) {
            return;
        }

        viewportHeight.value = listContainerRef.value.clientHeight;
        containerWidth.value = listContainerRef.value.clientWidth;
    }

    function onListScroll() {
        if (!listContainerRef.value) {
            return;
        }

        scrollTop.value = listContainerRef.value.scrollTop;
    }

    function resetListScroll() {
        if (!listContainerRef.value) {
            return;
        }

        listContainerRef.value.scrollTop = 0;
        scrollTop.value = 0;
    }

    function toggleOffline() {
        if (showOffline.value && !showOnline.value) {
            return;
        }

        showOffline.value = !showOffline.value;
    }

    function toggleOnline() {
        if (showOnline.value && !showOffline.value) {
            return;
        }

        showOnline.value = !showOnline.value;
    }

    function openPreview(event: ExploreEvent) {
        selectedEvent.value = event;
        previewOpen.value = true;
    }

    function closePreview() {
        previewOpen.value = false;
    }

    onMounted(() => {
        syncListMetrics();
        window.addEventListener('resize', syncListMetrics);

        if (listContainerRef.value) {
            resizeObserver = new ResizeObserver(syncListMetrics);
            resizeObserver.observe(listContainerRef.value);
        }
    });

    onBeforeUnmount(() => {
        window.removeEventListener('resize', syncListMetrics);
        resizeObserver?.disconnect();
    });

    watch([cityFilter, searchQuery, showOffline, showOnline], () => {
        resetListScroll();
    });

    watch(previewOpen, (isOpen) => {
        if (!isOpen) {
            selectedEvent.value = null;
        }
    });

    return {
        rowGap: ROW_GAP,
        rowHeight: ROW_HEIGHT,
        cityFilter,
        cityOptions,
        columnCount,
        filteredEvents,
        listContainerRef,
        onListScroll,
        openPreview,
        closePreview,
        previewOpen,
        searchQuery,
        selectedEvent,
        showOffline,
        showOnline,
        toggleOffline,
        toggleOnline,
        topSpacerHeight,
        bottomSpacerHeight,
        visibleCardCount,
        visibleRows,
    };
}