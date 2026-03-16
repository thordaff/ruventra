<script setup lang="ts">
import { useRouter } from 'vue-router';
import ExploreEventCard from '@/components/cards/ExploreEventCard.vue';
import ExploreEventFilterSidebar from '@/components/explore-events/ExploreEventFilterSidebar.vue';
import ExploreEventPreviewModal from '@/components/modal/ExploreEventPreviewModal.vue';
import { useExploreEventBrowse } from '@/composables/useExploreEventBrowse';
import type { ExploreEvent } from '@/lib/explore-events';

const router = useRouter();

const {
    bottomSpacerHeight,
    cityFilter,
    cityOptions,
    closePreview,
    columnCount,
    filteredEvents,
    listContainerRef,
    onListScroll,
    openPreview,
    previewOpen,
    rowGap,
    rowHeight,
    searchQuery,
    selectedEvent,
    showOffline,
    showOnline,
    toggleOffline,
    toggleOnline,
    topSpacerHeight,
    visibleCardCount,
    visibleRows,
} = useExploreEventBrowse();

function goToDetail(event: ExploreEvent) {
    closePreview();
    router.push({
        name: 'JelajahEventDetail',
        params: { id: event.id },
    });
}
</script>

<template>
    <div class="mx-auto w-full max-w-7xl px-3 sm:px-4 lg:px-6">
        <div class="grid gap-5 lg:grid-cols-[300px_minmax(0,1fr)]">
            <aside class="lg:sticky lg:top-24 lg:self-start">
                <ExploreEventFilterSidebar
                    :city-filter="cityFilter"
                    :city-options="cityOptions"
                    :search-value="searchQuery"
                    :show-offline="showOffline"
                    :show-online="showOnline"
                    @toggle:offline="toggleOffline"
                    @toggle:online="toggleOnline"
                    @update:city-filter="cityFilter = $event"
                    @update:search-value="searchQuery = $event"
                />
            </aside>

            <section class="min-w-0">
                <div class="mb-3 flex flex-col gap-1.5 text-sm text-slate-600 sm:flex-row sm:items-center sm:justify-between">
                    <p>
                        Menampilkan
                        <span class="font-semibold text-slate-900">{{ filteredEvents.length.toLocaleString('id-ID') }}</span>
                        event.
                    </p>
                    <p>
                        Dirender saat ini:
                        <span class="font-semibold text-sky-700">{{ visibleCardCount }}</span>
                        card di viewport.
                    </p>
                </div>

                <div
                    ref="listContainerRef"
                    class="h-[74vh] min-h-120 overflow-y-auto rounded-3xl border border-slate-200 bg-white shadow-sm"
                    @scroll.passive="onListScroll"
                >
                    <template v-if="filteredEvents.length">
                        <div :style="{ height: `${topSpacerHeight}px` }" />

                        <div
                            v-for="row in visibleRows"
                            :key="row.rowIndex"
                            class="px-4"
                            :style="{ height: `${rowHeight}px`, paddingBottom: `${rowGap}px` }"
                        >
                            <div
                                class="grid h-full gap-4"
                                :style="{ gridTemplateColumns: `repeat(${columnCount}, minmax(0, 1fr))` }"
                            >
                                <ExploreEventCard
                                    v-for="event in row.events"
                                    :key="event.id"
                                    :event="event"
                                    @click="openPreview(event)"
                                />
                            </div>
                        </div>

                        <div :style="{ height: `${bottomSpacerHeight}px` }" />
                    </template>

                    <div v-else class="flex h-full items-center justify-center px-4 text-center text-slate-500">
                        <div>
                            <p class="text-base font-semibold text-slate-700">Event tidak ditemukan</p>
                            <p class="mt-1 text-sm">Coba ubah kota, mode event, atau kata kunci pencarian.</p>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <ExploreEventPreviewModal
            :event="selectedEvent"
            :open="previewOpen"
            @update:open="previewOpen = $event"
            @view-detail="goToDetail"
        />
    </div>
</template>
