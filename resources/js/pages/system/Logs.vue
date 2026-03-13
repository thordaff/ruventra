<script setup lang="ts">
import axios from 'axios';
import { Ban, ChevronLeft, ChevronRight, RefreshCw, Search, ShieldOff, XCircle } from 'lucide-vue-next';
import { onMounted, ref, watch } from 'vue';
import LogLabel from '@/components/LogLabel.vue';

// ── Types ──────────────────────────────────────────────────────────────────
interface LogUser {
    id: number;
    name: string;
    email: string;
}

interface SystemLog {
    id: number;
    user_id: number | null;
    action: string;
    description: string;
    metadata: Record<string, unknown> | null;
    ip_address: string | null;
    user_agent: string | null;
    severity: 'info' | 'warning' | 'error' | 'critical';
    label_color: string;
    created_at: string;
    user: LogUser | null;
}

interface PaginatedResponse {
    data: SystemLog[];
    current_page: number;
    last_page: number;
    total: number;
    per_page: number;
}

// ── State ─────────────────────────────────────────────────────────────────
const logs        = ref<SystemLog[]>([]);
const loading     = ref(false);
const currentPage = ref(1);
const lastPage    = ref(1);
const total       = ref(0);

const filterAction   = ref('');
const filterSeverity = ref('');
const filterUserId   = ref('');
const search         = ref('');

const suspendModal  = ref(false);
const suspendTarget = ref<LogUser | null>(null);
const suspendReason = ref('');
const suspendError  = ref('');
const suspending    = ref(false);

const detailLog = ref<SystemLog | null>(null);

// ── Filter options ────────────────────────────────────────────────────────
const actionOptions = [
    { value: '',                    label: 'Semua Aksi' },
    { value: 'login',               label: 'Login' },
    { value: 'logout',              label: 'Logout' },
    { value: 'duplicate_session',   label: 'Duplikat Sesi (Login Ganda)' },
    { value: 'session_invalidated', label: 'Sesi Diakhiri Otomatis' },
    { value: 'event_created',       label: 'Event Dibuat' },
    { value: 'account_suspended',   label: 'Akun Di-suspend' },
    { value: 'account_unsuspended', label: 'Akun Di-unsuspend' },
];

const severityOptions = [
    { value: '',         label: 'Semua Severity' },
    { value: 'info',     label: 'Info' },
    { value: 'warning',  label: 'Warning' },
    { value: 'error',    label: 'Error' },
    { value: 'critical', label: 'Critical' },
];

// ── Fetch ─────────────────────────────────────────────────────────────────
async function fetchLogs() {
    loading.value = true;
    try {
        const params: Record<string, string | number> = { page: currentPage.value, per_page: 30 };
        if (filterAction.value)   params.action   = filterAction.value;
        if (filterSeverity.value) params.severity  = filterSeverity.value;
        if (filterUserId.value)   params.user_id   = filterUserId.value;
        if (search.value)         params.search    = search.value;

        const res = await axios.get<PaginatedResponse>('/api/system-logs', { params });
        logs.value        = res.data.data;
        currentPage.value = res.data.current_page;
        lastPage.value    = res.data.last_page;
        total.value       = res.data.total;
    } finally {
        loading.value = false;
    }
}

onMounted(fetchLogs);

// Reset page on filter change
watch([filterAction, filterSeverity, filterUserId, search], () => {
    currentPage.value = 1;
    fetchLogs();
});

function prevPage() { if (currentPage.value > 1) { currentPage.value--; fetchLogs(); } }
function nextPage() { if (currentPage.value < lastPage.value) { currentPage.value++; fetchLogs(); } }

// ── Suspend helpers ───────────────────────────────────────────────────────
function openSuspend(user: LogUser) {
    suspendTarget.value = user;
    suspendReason.value = '';
    suspendError.value  = '';
    suspendModal.value  = true;
}

async function confirmSuspend() {
    if (!suspendTarget.value) return;
    if (!suspendReason.value.trim()) { suspendError.value = 'Alasan wajib diisi.'; return; }
    suspending.value = true;
    try {
        await axios.post(`/api/users/${suspendTarget.value.id}/suspend`, { reason: suspendReason.value });
        suspendModal.value = false;
        fetchLogs();
    } catch (e: any) {
        suspendError.value = e.response?.data?.message ?? 'Gagal men-suspend akun.';
    } finally {
        suspending.value = false;
    }
}

// ── Label helpers ─────────────────────────────────────────────────────────
// labelColorMap, labelClass, actionIcon, actionLabel — all from @/lib/logColors

function formatDate(iso: string) {
    return new Date(iso).toLocaleString('id-ID', {
        day: '2-digit', month: 'short', year: 'numeric',
        hour: '2-digit', minute: '2-digit',
    });
}

</script>

<template>
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">System Logs</h1>
                <p class="mt-1 text-sm text-gray-500">
                    Pantau aktivitas seluruh pengguna — total {{ total }} log tersimpan.
                </p>
            </div>
            <button
                class="flex items-center gap-2 rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50"
                @click="fetchLogs"
            >
                <RefreshCw class="h-4 w-4" :class="{ 'animate-spin': loading }" />
                Refresh
            </button>
        </div>

        <!-- Filters -->
        <div class="grid grid-cols-1 gap-3 rounded-xl border bg-white p-4 shadow-sm sm:grid-cols-2 lg:grid-cols-4">
            <!-- Search -->
            <div class="relative">
                <Search class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400" />
                <input
                    v-model="search"
                    type="text"
                    placeholder="Cari deskripsi…"
                    class="w-full rounded-lg border border-gray-200 py-2 pl-9 pr-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                />
            </div>

            <!-- Action filter -->
            <select
                v-model="filterAction"
                class="rounded-lg border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
            >
                <option v-for="opt in actionOptions" :key="opt.value" :value="opt.value">{{ opt.label }}</option>
            </select>

            <!-- Severity filter -->
            <select
                v-model="filterSeverity"
                class="rounded-lg border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
            >
                <option v-for="opt in severityOptions" :key="opt.value" :value="opt.value">{{ opt.label }}</option>
            </select>

            <!-- Legend -->
            <div class="flex flex-wrap items-center gap-2 text-xs">
                <span class="rounded border border-green-200 bg-green-100 px-2 py-0.5 text-green-700">Login</span>
                <span class="rounded border border-blue-200 bg-blue-100 px-2 py-0.5 text-blue-700">Logout</span>
                <span class="rounded border border-yellow-200 bg-yellow-100 px-2 py-0.5 text-yellow-700">Warning</span>
                <span class="rounded border border-red-200 bg-red-100 px-2 py-0.5 text-red-700 font-semibold">Critical</span>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-hidden rounded-xl border bg-white shadow-sm">
            <div v-if="loading" class="flex items-center justify-center py-16 text-gray-400">
                <RefreshCw class="mr-2 h-5 w-5 animate-spin" /> Memuat…
            </div>

            <table v-else class="min-w-full divide-y divide-gray-100">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Waktu</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Label</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Aksi</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Deskripsi</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">User</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">IP</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-500">Aksi User</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    <tr
                        v-for="log in logs"
                        :key="log.id"
                        :class="log.severity === 'critical' ? 'bg-red-50' : ''"
                        class="hover:bg-gray-50 cursor-pointer"
                        @click="detailLog = log"
                    >
                        <!-- Time -->
                        <td class="whitespace-nowrap px-4 py-3 text-xs text-gray-500">
                            {{ formatDate(log.created_at) }}
                        </td>

                        <!-- Label badge -->
                        <td class="px-4 py-3">
                            <LogLabel :action="log.action" :color="log.label_color" />
                        </td>

                        <!-- Action slug -->
                        <td class="px-4 py-3 text-xs font-mono text-gray-600">{{ log.action }}</td>

                        <!-- Description -->
                        <td class="max-w-xs truncate px-4 py-3 text-sm text-gray-700">{{ log.description }}</td>

                        <!-- User -->
                        <td class="px-4 py-3 text-sm text-gray-700">
                            <span v-if="log.user">
                                <span class="font-medium">{{ log.user.name }}</span>
                                <br />
                                <span class="text-xs text-gray-400">{{ log.user.email }}</span>
                            </span>
                            <span v-else class="text-gray-400">—</span>
                        </td>

                        <!-- IP -->
                        <td class="whitespace-nowrap px-4 py-3 text-xs text-gray-500">{{ log.ip_address ?? '—' }}</td>

                        <!-- Actions — hanya muncul saat duplikat sesi aktif -->
                        <td class="px-4 py-3 text-center" @click.stop>
                            <div v-if="log.action === 'duplicate_session' && log.user" class="flex items-center justify-center">
                                <button
                                    class="flex items-center gap-1 rounded border border-red-200 bg-red-50 px-2 py-1 text-xs text-red-600 hover:bg-red-100"
                                    title="Suspend akun"
                                    @click="openSuspend(log.user!)"
                                >
                                    <Ban class="h-3 w-3" /> Suspend
                                </button>
                            </div>
                            <span v-else class="text-gray-300">—</span>
                        </td>
                    </tr>

                    <tr v-if="logs.length === 0 && !loading">
                        <td colspan="7" class="py-12 text-center text-sm text-gray-400">Tidak ada log ditemukan.</td>
                    </tr>
                </tbody>
            </table>

            <!-- Pagination -->
            <div v-if="lastPage > 1" class="flex items-center justify-between border-t px-4 py-3">
                <span class="text-sm text-gray-500">Halaman {{ currentPage }} / {{ lastPage }}</span>
                <div class="flex gap-2">
                    <button
                        :disabled="currentPage <= 1"
                        class="flex items-center gap-1 rounded border px-3 py-1.5 text-sm disabled:opacity-40"
                        @click="prevPage"
                    >
                        <ChevronLeft class="h-4 w-4" /> Sebelumnya
                    </button>
                    <button
                        :disabled="currentPage >= lastPage"
                        class="flex items-center gap-1 rounded border px-3 py-1.5 text-sm disabled:opacity-40"
                        @click="nextPage"
                    >
                        Berikutnya <ChevronRight class="h-4 w-4" />
                    </button>
                </div>
            </div>
        </div>

        <!-- Log Detail Modal -->
        <Teleport to="body">
            <div v-if="detailLog" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 p-4">
                <div class="w-full max-w-lg rounded-2xl bg-white shadow-2xl">
                    <div class="flex items-center justify-between border-b px-6 py-4">
                        <h2 class="font-semibold text-gray-900">Detail Log #{{ detailLog.id }}</h2>
                        <button @click="detailLog = null" class="text-gray-400 hover:text-gray-700">
                            <XCircle class="h-5 w-5" />
                        </button>
                    </div>
                    <div class="space-y-3 px-6 py-4 text-sm">
                        <div class="flex gap-2">
                            <span class="w-28 font-medium text-gray-500">Waktu</span>
                            <span>{{ formatDate(detailLog.created_at) }}</span>
                        </div>
                        <div class="flex gap-2">
                            <span class="w-28 font-medium text-gray-500">Aksi</span>
                            <span class="font-mono">{{ detailLog.action }}</span>
                        </div>
                        <div class="flex gap-2">
                            <span class="w-28 font-medium text-gray-500">Severity</span>
                            <LogLabel :action="detailLog.action" :color="detailLog.label_color" />
                        </div>
                        <div class="flex gap-2">
                            <span class="w-28 font-medium text-gray-500">Deskripsi</span>
                            <span>{{ detailLog.description }}</span>
                        </div>
                        <div class="flex gap-2">
                            <span class="w-28 font-medium text-gray-500">User</span>
                            <span>{{ detailLog.user ? `${detailLog.user.name} (${detailLog.user.email})` : '—' }}</span>
                        </div>
                        <div class="flex gap-2">
                            <span class="w-28 font-medium text-gray-500">IP Address</span>
                            <span>{{ detailLog.ip_address ?? '—' }}</span>
                        </div>
                        <div class="flex gap-2">
                            <span class="w-28 font-medium text-gray-500">User Agent</span>
                            <span class="break-all text-xs text-gray-500">{{ detailLog.user_agent ?? '—' }}</span>
                        </div>
                        <div v-if="detailLog.metadata" class="flex gap-2">
                            <span class="w-28 font-medium text-gray-500">Metadata</span>
                            <pre class="overflow-auto rounded bg-gray-50 p-2 text-xs">{{ JSON.stringify(detailLog.metadata, null, 2) }}</pre>
                        </div>
                    </div>
                </div>
            </div>
        </Teleport>

        <!-- Suspend Modal -->
        <Teleport to="body">
            <div v-if="suspendModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 p-4">
                <div class="w-full max-w-md rounded-2xl bg-white shadow-2xl">
                    <div class="flex items-center gap-3 border-b px-6 py-4">
                        <ShieldOff class="h-5 w-5 text-red-500" />
                        <h2 class="font-semibold text-gray-900">Suspend Akun</h2>
                    </div>
                    <div class="px-6 py-4 space-y-4">
                        <p class="text-sm text-gray-600">
                            Anda akan men-suspend akun
                            <span class="font-medium text-gray-900">{{ suspendTarget?.email }}</span>.
                            Pengguna tidak akan bisa login sampai akun diaktifkan kembali.
                        </p>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700">Alasan Suspend <span class="text-red-500">*</span></label>
                            <textarea
                                v-model="suspendReason"
                                rows="3"
                                placeholder="Misal: Pelanggaran berulang, duplicasi sesi mencurigakan, dll."
                                class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-400"
                            />
                            <p v-if="suspendError" class="mt-1 text-xs text-red-500">{{ suspendError }}</p>
                        </div>
                    </div>
                    <div class="flex justify-end gap-3 border-t px-6 py-4">
                        <button
                            class="rounded-lg border px-4 py-2 text-sm text-gray-600 hover:bg-gray-50"
                            @click="suspendModal = false"
                        >Batal</button>
                        <button
                            :disabled="suspending"
                            class="flex items-center gap-2 rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700 disabled:opacity-50"
                            @click="confirmSuspend"
                        >
                            <Ban class="h-4 w-4" />
                            {{ suspending ? 'Memproses…' : 'Suspend Sekarang' }}
                        </button>
                    </div>
                </div>
            </div>
        </Teleport>
    </div>
</template>
