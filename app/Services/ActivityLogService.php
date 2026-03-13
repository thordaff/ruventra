<?php

namespace App\Services;

use App\Models\SystemLog;
use App\Models\User;
use Illuminate\Http\Request;

class ActivityLogService
{
    /**
     * Write a log entry, optionally derived from a Request for IP / UA.
     */
    public static function log(
        string $action,
        string $description,
        ?User $user = null,
        string $severity = 'info',
        ?string $labelColor = null,
        array $metadata = [],
        ?Request $request = null,
    ): SystemLog {
        $resolvedColor = $labelColor ?? SystemLog::colorForSeverity($severity);
        $req = $request ?? request();

        return SystemLog::create([
            'user_id'    => $user?->id,
            'action'     => $action,
            'description'=> $description,
            'metadata'   => empty($metadata) ? null : $metadata,
            'ip_address' => $req?->ip(),
            'user_agent' => $req?->userAgent(),
            'severity'   => $severity,
            'label_color'=> $resolvedColor,
        ]);
    }

    // ── Convenience wrappers ───────────────────────────────────────────────

    public static function login(User $user, Request $request): void
    {
        self::log('login', "User '{$user->email}' logged in.", $user, 'info', 'green', [], $request);
    }

    public static function logout(User $user, Request $request): void
    {
        self::log('logout', "User '{$user->email}' logged out.", $user, 'info', 'blue', [], $request);
    }

    public static function duplicateSession(User $user, Request $request, int $attempts): void
    {
        // Severity escalates at 3+ attempts
        $critical = $attempts >= 3;
        $severity = $critical ? 'critical' : 'warning';
        $color    = $critical ? 'red'      : 'yellow';

        self::log(
            'duplicate_session',
            $critical
                ? "ALERT: User '{$user->email}' attempted duplicate login {$attempts} time(s)! Account may be under threat."
                : "User '{$user->email}' attempted to login while already active elsewhere (attempt #{$attempts}).",
            $user,
            $severity,
            $color,
            ['attempts' => $attempts, 'new_ip' => $request->ip()],
            $request,
        );
    }

    public static function accountSuspended(User $target, User $actor, string $reason, Request $request): void
    {
        self::log(
            'account_suspended',
            "Account '{$target->email}' was suspended by '{$actor->email}'. Reason: {$reason}",
            $target,
            'critical',
            'red',
            ['actor_id' => $actor->id, 'reason' => $reason],
            $request,
        );
    }

    public static function accountUnsuspended(User $target, User $actor, Request $request): void
    {
        self::log(
            'account_unsuspended',
            "Account '{$target->email}' was unsuspended by '{$actor->email}'.",
            $target,
            'info',
            'green',
            ['actor_id' => $actor->id],
            $request,
        );
    }

    public static function eventCreated(User $user, string $eventName, Request $request): void
    {
        self::log(
            'event_created',
            "User '{$user->email}' created event: {$eventName}",
            $user,
            'info',
            'purple',
            [],
            $request,
        );
    }
}
