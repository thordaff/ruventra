<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserManagementController extends Controller
{
    /**
     * List all users (for system-log user filter & user management page).
     */
    public function index()
    {
        return response()->json(
            User::with('roles:id,name,label')
                ->select('id', 'name', 'email', 'suspended_at', 'suspended_reason', 'duplicate_attempts', 'created_at')
                ->orderBy('name')
                ->get()
        );
    }

    /**
     * Suspend a user account.
     */
    public function suspend(Request $request, User $user)
    {
        $request->validate(['reason' => ['required', 'string', 'max:255']]);

        if ($user->id === Auth::id()) {
            return response()->json(['message' => 'Tidak dapat men-suspend akun sendiri.'], 422);
        }

        $user->suspended_at     = now();
        $user->suspended_reason = $request->reason;
        // Invalidate active session
        $user->session_token    = null;
        $user->save();

        ActivityLogService::accountSuspended($user, Auth::user(), $request->reason, $request);

        return response()->json(['message' => "Akun '{$user->email}' berhasil di-suspend."]);
    }

    /**
     * Unsuspend (restore) a user account.
     */
    public function unsuspend(Request $request, User $user)
    {
        $user->suspended_at     = null;
        $user->suspended_reason = null;
        $user->duplicate_attempts = 0;
        $user->save();

        ActivityLogService::accountUnsuspended($user, Auth::user(), $request);

        return response()->json(['message' => "Akun '{$user->email}' berhasil di-unsuspend."]);
    }
}
