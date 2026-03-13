<?php

namespace App\Http\Controllers;

use App\Models\SystemLog;
use Illuminate\Http\Request;

class SystemLogController extends Controller
{
    /**
     * Return paginated system logs with optional filters.
     *
     * Query params:
     *   action   – filter by action slug (login, logout, duplicate_session, …)
     *   severity – filter by severity (info, warning, error, critical)
     *   user_id  – filter by user
     *   search   – free-text search on description
     *   per_page – default 30
     */
    public function index(Request $request)
    {
        $query = SystemLog::with('user:id,name,email')
            ->orderByDesc('created_at');

        if ($action = $request->query('action')) {
            $query->where('action', $action);
        }

        if ($severity = $request->query('severity')) {
            $query->where('severity', $severity);
        }

        if ($userId = $request->query('user_id')) {
            $query->where('user_id', $userId);
        }

        if ($search = $request->query('search')) {
            $query->where('description', 'like', '%' . $search . '%');
        }

        $perPage = min((int) ($request->query('per_page', 30)), 100);

        return response()->json($query->paginate($perPage));
    }
}
