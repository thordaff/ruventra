<?php

namespace App\Http\Middleware;

use App\Services\ActivityLogService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Ensures only one active session per user by comparing the session-stored
 * token against the token persisted in the users table.
 * If another login has replaced the token, this session is silently invalidated.
 */
class EnsureSingleSession
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            /** @var \App\Models\User $user */
            $user         = Auth::user();
            $sessionToken = $request->session()->get('session_token');

            if (empty($user->session_token) || $sessionToken !== $user->session_token) {
                // Log the forced logout before invalidating
                ActivityLogService::log(
                    'session_invalidated',
                    "Sesi '{$user->email}' diakhiri otomatis karena login dari perangkat lain.",
                    $user,
                    'warning',
                    'orange',
                    ['old_ip' => $request->ip()],
                    $request,
                );

                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                if ($request->expectsJson()) {
                    return response()->json([
                        'status'  => 'session_invalidated',
                        'message' => 'Sesi Anda telah berakhir karena akun login di perangkat lain.',
                    ], 401);
                }

                return redirect('/login')->with('error', 'Sesi Anda telah berakhir.');
            }
        }

        return $next($request);
    }
}

