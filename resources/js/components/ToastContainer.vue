<script setup lang="ts">
import ToastNotification from '@/components/ToastNotification.vue';
import { useToast } from '@/composables/useToast';

const { toasts } = useToast();
</script>

<template>
    <!-- Fixed top-right stack, above everything -->
    <div class="pointer-events-none fixed right-4 top-4 z-9999 flex flex-col gap-2">
        <TransitionGroup
            name="toast"
            tag="div"
            class="flex flex-col gap-2"
        >
            <ToastNotification
                v-for="t in toasts"
                :key="t.id"
                :toast="t"
            />
        </TransitionGroup>
    </div>
</template>

<style scoped>
.toast-enter-active {
    transition: all 0.3s ease;
}
.toast-leave-active {
    transition: all 0.25s ease;
}
.toast-enter-from {
    opacity: 0;
    transform: translateX(100%);
}
.toast-leave-to {
    opacity: 0;
    transform: translateX(100%);
}
.toast-move {
    transition: transform 0.25s ease;
}
</style>
