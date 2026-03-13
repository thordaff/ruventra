import {
    AlertTriangle,
    CheckCircle,
    Info,
    LogIn,
    LogOut,
    ShieldOff,
    UserCheck,
    XCircle,
} from 'lucide-vue-next';

// ── Label colour → Tailwind classes ───────────────────────────────────────
export const labelColorMap: Record<string, string> = {
    green:  'bg-green-100  text-green-700  border-green-200',
    blue:   'bg-blue-100   text-blue-700   border-blue-200',
    yellow: 'bg-yellow-100 text-yellow-700 border-yellow-200',
    orange: 'bg-orange-100 text-orange-700 border-orange-200',
    red:    'bg-red-100    text-red-700    border-red-200',
    purple: 'bg-purple-100 text-purple-700 border-purple-200',
    gray:   'bg-gray-100   text-gray-600   border-gray-200',
};

export function labelClass(color: string): string {
    return labelColorMap[color] ?? labelColorMap.gray;
}

// ── Action → icon component ───────────────────────────────────────────────
export const actionIconMap: Record<string, unknown> = {
    login:               LogIn,
    logout:              LogOut,
    duplicate_session:   AlertTriangle,
    session_invalidated: XCircle,
    event_created:       CheckCircle,
    account_suspended:   ShieldOff,
    account_unsuspended: UserCheck,
};

export function actionIcon(action: string): unknown {
    return actionIconMap[action] ?? Info;
}

// ── Action → human-readable label ─────────────────────────────────────────
export const actionLabel: Record<string, string> = {
    login:               'Login',
    logout:              'Logout',
    duplicate_session:   'Login Ganda',
    session_invalidated: 'Sesi Berakhir',
    event_created:       'Event Dibuat',
    account_suspended:   'Akun Suspend',
    account_unsuspended: 'Akun Aktif',
};
