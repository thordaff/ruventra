<script setup lang="ts">
import { ArrowRight } from 'lucide-vue-next';
import ExploreEventOverviewGrid from '@/components/explore-events/ExploreEventOverviewGrid.vue';
import ModalLayout from '@/components/modal/Layout.vue';
import { Button } from '@/components/ui/button';
import { DialogFooter } from '@/components/ui/dialog';
import {
    getExploreEventModeBadgeClass,
    getExploreEventModeLabel,
} from '@/lib/explore-events';
import type { ExploreEvent } from '@/lib/explore-events';

const props = defineProps<{
    event: ExploreEvent | null;
    open: boolean;
}>();

const emit = defineEmits<{
    'update:open': [value: boolean];
    'view-detail': [event: ExploreEvent];
}>();

function closeModal() {
    emit('update:open', false);
}

function viewDetail() {
    if (!props.event) {
        return;
    }

    emit('view-detail', props.event);
}
</script>

<template>
    <template v-if="event">
        <ModalLayout
            :open="open"
            :thumbnail="event.thumbnail"
            :thumbnail-alt="event.title"
            :title="event.title"
            :category="event.category"
            category-color-class="bg-slate-100 text-slate-700"
            content-class="sm:max-w-2xl"
            :description="event.summary"
            thumbnail-class="h-48 sm:h-64"
            @update:open="emit('update:open', $event)"
        >
            <template #badges>
                <span
                    class="rounded-full px-3 py-1 text-xs font-medium"
                    :class="getExploreEventModeBadgeClass(event.mode, 'soft')"
                >
                    {{ getExploreEventModeLabel(event.mode) }}
                </span>
            </template>

            <ExploreEventOverviewGrid :event="event" variant="preview" />

            <p class="text-sm leading-7 text-slate-600">
                {{ event.description }}
            </p>

            <DialogFooter class="gap-2 sm:justify-between">
                <Button variant="secondary" type="button" @click="closeModal">
                    Tutup
                </Button>
                <Button type="button" @click="viewDetail">
                    Lihat selengkapnya
                    <ArrowRight class="size-4" />
                </Button>
            </DialogFooter>
        </ModalLayout>
    </template>
</template>