<script setup lang="ts">
import { CheckCircle, Info, TriangleAlert, X, XCircle } from 'lucide-vue-next';
import { onMounted, onUnmounted, ref } from 'vue';
import type { ToastItem, ToastType } from '@/composables/useToast';
import { useToast } from '@/composables/useToast';

const props = defineProps<{ toast: ToastItem }>();
const { dismiss } = useToast();

const progress = ref(100);
let startTime: number;
let raf: number;

const iconMap: Record<ToastType, typeof CheckCircle> = {
    success: CheckCircle,
    error: XCircle,
    warning: TriangleAlert,
    info: Info,
};

const colorMap: Record<ToastType, { bar: string; icon: string; border: string }> = {
    success: { bar: 'bg-green-500',  icon: 'text-green-500',  border: 'border-green-200' },
    error:   { bar: 'bg-red-500',    icon: 'text-red-500',    border: 'border-red-200'   },
    warning: { bar: 'bg-yellow-400', icon: 'text-yellow-500', border: 'border-yellow-200'},
    info:    { bar: 'bg-indigo-500', icon: 'text-indigo-500', border: 'border-indigo-200'},
};

function tick(now: number) {
    if (!startTime) startTime = now;
    const elapsed = now - startTime;
    progress.value = Math.max(0, 100 - (elapsed / props.toast.duration) * 100);
    if (progress.value > 0) raf = requestAnimationFrame(tick);
}

onMounted(() => { raf = requestAnimationFrame(tick); });
onUnmounted(() => cancelAnimationFrame(raf));
</script>

<template>
    <div
        class="pointer-events-auto relative w-80 overflow-hidden rounded-xl border bg-white shadow-lg"
        :class="colorMap[toast.type].border"
    >
        <!-- Content row -->
        <div class="flex items-start gap-3 p-4 pr-10">
            <component
                :is="iconMap[toast.type]"
                class="mt-0.5 h-5 w-5 shrink-0"
                :class="colorMap[toast.type].icon"
            />
            <p class="text-sm leading-snug text-gray-800">{{ toast.message }}</p>
        </div>

        <!-- Close button -->
        <button
            type="button"
            class="absolute right-2 top-2 rounded p-1 text-gray-400 hover:text-gray-600"
            @click="dismiss(toast.id)"
        >
            <X class="h-4 w-4" />
        </button>

        <!-- Timer bar -->
        <div class="h-1 w-full bg-gray-100">
            <div
                class="h-full transition-none"
                :class="colorMap[toast.type].bar"
                :style="{ width: progress + '%' }"
            />
        </div>
    </div>
</template>
