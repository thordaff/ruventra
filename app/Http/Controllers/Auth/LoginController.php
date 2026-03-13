<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Authenticate without creating a session yet
        if (! Auth::validate($credentials)) {
            return response()->json([
                'errors' => ['email' => ['Email atau password salah.']],
            ], 422);
        }

        /** @var \App\Models\User $user */
        $user = \App\Models\User::where('email', $credentials['email'])->first();

        // ── Suspension check ───────────────────────────────────────────────
        if ($user->isSuspended()) {
            return response()->json([
                'status'  => 'suspended',
                'message' => 'Akun Anda telah disuspend. Alasan: ' . ($user->suspended_reason ?? '-'),
            ], 403);
        }

        // ── Duplicate session check ────────────────────────────────────────
        $hasActiveSession = ! empty($user->session_token);

        if ($hasActiveSession && ! $request->boolean('force')) {
            // Increment attempt counter
            $attempts = $user->duplicate_attempts + 1;
            $user->duplicate_attempts = $attempts;
            $user->save();

            // Log the duplicate attempt (escalates to red at 3+)
            ActivityLogService::duplicateSession($user, $request, $attempts);

            return response()->json([
                'status'   => 'duplicate_session',
                'attempts' => $attempts,
                'message'  => 'Akun ini sudah aktif di perangkat lain.',
            ], 409);
        }

        // ── Proceed with login ─────────────────────────────────────────────
        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        // Generate a new session token and store it
        $token = Str::random(64);
        $user->session_token       = $token;
        $user->duplicate_attempts  = 0;  // reset on successful login
        $user->save();

        // Keep the token in the Laravel session so the middleware can compare
        $request->session()->put('session_token', $token);

        ActivityLogService::login($user, $request);

        return response()->json(['user' => $user->load('roles')]);
    }

    public function logout(Request $request)
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();

        if ($user) {
            ActivityLogService::logout($user, $request);
            $user->session_token = null;
            $user->save();
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['message' => 'Logged out']);
    }
}

