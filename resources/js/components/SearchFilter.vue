<script setup lang="ts">
import { Search, X } from 'lucide-vue-next';
import { ref, watch } from 'vue';
import { Input } from '@/components/ui/input';

const props = withDefaults(
    defineProps<{
        /**
         * Currently active filter tag. Pass '' or the first item in `filters`
         * as the "Show all" sentinel. Two-way via v-model:filter.
         */
        filter: string;
        /**
         * Debounced search string. Two-way via v-model:search.
         */
        search: string;
        /**
         * List of filter tag labels. First item is treated as "all" (always shown uncoloured when selected if needed).
         * @example ['Semua', 'Musik', 'Teknologi']
         */
        filters?: string[];
        /** Input placeholder text */
        placeholder?: string;
        /** Debounce delay in ms */
        debounce?: number;
    }>(),
    {
        filters: () => [],
        placeholder: 'Cari…',
        debounce: 300,
    },
);

const emit = defineEmits<{
    'update:search': [value: string];
    'update:filter': [value: string];
}>();

const localInput = ref(props.search);

// Keep localInput in sync if parent resets search externally
watch(
    () => props.search,
    (val) => {
        if (val !== localInput.value) localInput.value = val;
    },
);

let timer: ReturnType<typeof setTimeout>;
watch(localInput, (val) => {
    clearTimeout(timer);
    timer = setTimeout(() => emit('update:search', val), props.debounce);
});

function clearSearch() {
    localInput.value = '';
    emit('update:search', '');
}

function setFilter(tag: string) {
    emit('update:filter', tag);
}
</script>

<template>
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <!-- Search box -->
        <div class="relative w-full sm:max-w-xs">
            <Search class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400" />
            <Input v-model="localInput" :placeholder="placeholder" class="pl-9 pr-9" />
            <button
                v-if="localInput"
                type="button"
                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600"
                @click="clearSearch"
            >
                <X class="h-4 w-4" />
            </button>
        </div>

        <!-- Filter tags -->
        <div v-if="filters.length" class="flex flex-wrap gap-2">
            <button
                v-for="tag in filters"
                :key="tag"
                type="button"
                class="rounded-full border px-3 py-1 text-xs font-medium transition"
                :class="
                    filter === tag
                        ? 'border-indigo-500 bg-indigo-500 text-white'
                        : 'border-gray-200 bg-white text-gray-600 hover:border-indigo-300 hover:text-indigo-600'
                "
                @click="setFilter(tag)"
            >
                {{ tag }}
            </button>
        </div>
    </div>
</template>
