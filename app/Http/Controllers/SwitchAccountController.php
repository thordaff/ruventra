<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Role;

class SwitchAccountController extends Controller
{
    public function switch($role)
    {
        $user = Auth::user();
        $targetRole = Role::where('name', $role)->firstOrFail();

        if (!$user->roles->contains($targetRole)) {
            abort(403, 'You do not have this role.');
        }

        session(['active_role' => $role]);
        return redirect()->back()->with('status', 'Switched to ' . $role . ' role.');
    }
}
