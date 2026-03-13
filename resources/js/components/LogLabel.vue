<script setup lang="ts">
import { computed } from 'vue';
import { actionIcon, actionLabel, labelClass } from '@/lib/logColors';

const props = defineProps<{
    action: string;
    color: string;
}>();

const icon   = computed(() => actionIcon(props.action));
const label  = computed(() => actionLabel[props.action] ?? props.action);
const cls    = computed(() => labelClass(props.color));
</script>

<template>
    <span
        :class="cls"
        class="inline-flex items-center gap-1 rounded-full border px-2.5 py-0.5 text-xs font-medium"
    >
        <component :is="icon" class="h-3 w-3" />
        {{ label }}
    </span>
</template>
