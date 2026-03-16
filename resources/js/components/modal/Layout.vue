<script setup lang="ts">
import {
    Dialog,
    DialogDescription,
    DialogHeader,
    DialogScrollContent,
    DialogTitle,
} from '@/components/ui/dialog';
import { cn } from '@/lib/utils';

const props = withDefaults(defineProps<{
    open: boolean;
    thumbnail: string;
    thumbnailAlt?: string;
    title: string;
    category?: string;
    categoryColorClass?: string;
    description: string;
    contentClass?: string;
    thumbnailClass?: string;
}>(), {
    category: '',
    contentClass: '',
    thumbnailClass: 'h-48 sm:h-64',
});

const emit = defineEmits<{ 'update:open': [value: boolean] }>();
</script>

<template>
    <Dialog :open="props.open" @update:open="emit('update:open', $event)">
        <DialogScrollContent :class="cn('max-h-[calc(100dvh-2rem)] sm:max-w-2xl', props.contentClass)">
            <!-- Thumbnail header -->
            <div :class="cn('-mx-6 -mt-6 mb-4 overflow-hidden rounded-t-lg border-b bg-slate-100', props.thumbnailClass)">
                <img :src="thumbnail" :alt="thumbnailAlt ?? title" class="h-full w-full object-cover" />
            </div>

            <DialogHeader>
                <div v-if="$slots.badges" class="flex flex-wrap gap-2">
                    <slot name="badges" />
                </div>
                <div class="flex items-start justify-between gap-2">
                    <DialogTitle class="text-xl">{{ title }}</DialogTitle>
                    <span
                        v-if="category"
                        class="shrink-0 rounded-full px-2.5 py-1 text-xs font-medium"
                        :class="categoryColorClass ?? 'bg-gray-100 text-gray-600'"
                    >
                        {{ category }}
                    </span>
                </div>
                <DialogDescription class="mt-2 text-sm leading-relaxed text-gray-600">
                    {{ description }}
                </DialogDescription>
            </DialogHeader>

            <!-- Page-specific content injected here -->
            <slot />
        </DialogScrollContent>
    </Dialog>
</template>
