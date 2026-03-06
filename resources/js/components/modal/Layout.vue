<script setup lang="ts">
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';

const props = defineProps<{
    open: boolean;
    thumbnail: string;
    thumbnailAlt?: string;
    title: string;
    category: string;
    categoryColorClass?: string;
    description: string;
}>();

const emit = defineEmits<{ 'update:open': [value: boolean] }>();
</script>

<template>
    <Dialog :open="props.open" @update:open="emit('update:open', $event)">
        <DialogContent class="max-h-[90vh] max-w-2xl overflow-y-auto">
            <!-- Thumbnail header -->
            <div class="-mx-6 -mt-6 mb-4 h-48 overflow-hidden rounded-t-lg">
                <img :src="thumbnail" :alt="thumbnailAlt ?? title" class="h-full w-full object-cover" />
            </div>

            <DialogHeader>
                <div class="flex items-start justify-between gap-2">
                    <DialogTitle class="text-xl">{{ title }}</DialogTitle>
                    <span
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
        </DialogContent>
    </Dialog>
</template>
