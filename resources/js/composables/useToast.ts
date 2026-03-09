import { ref } from 'vue';

export type ToastType = 'success' | 'error' | 'info' | 'warning';

export interface ToastItem {
    id: number;
    message: string;
    type: ToastType;
    duration: number; // ms
}

const toasts = ref<ToastItem[]>([]);
let nextId = 0;

export function useToast() {
    function toast(message: string, type: ToastType = 'info', duration = 5000) {
        const id = ++nextId;
        toasts.value.push({ id, message, type, duration });
        setTimeout(() => dismiss(id), duration);
    }

    function dismiss(id: number) {
        const idx = toasts.value.findIndex((t) => t.id === id);
        if (idx !== -1) toasts.value.splice(idx, 1);
    }

    return { toasts, toast, dismiss };
}
