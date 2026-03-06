<?php

namespace App\Http\Controllers;

use App\Models\NavItem;
use Illuminate\Http\Request;

class NavItemController extends Controller
{
    public function index(Request $request)
    {
        $roleIds = $request->user()->roles->pluck('id');

        $mainItems = NavItem::where('section', 'main')
            ->whereNull('parent_id')
            ->whereHas('roles', fn ($q) => $q->whereIn('roles.id', $roleIds))
            ->with(['children' => function ($q) use ($roleIds) {
                $q->whereHas('roles', fn ($q2) => $q2->whereIn('roles.id', $roleIds))
                    ->orderBy('order');
            }])
            ->orderBy('order')
            ->get(['id', 'title', 'href', 'icon', 'is_external', 'order']);

        $footerItems = NavItem::where('section', 'footer')
            ->whereNull('parent_id')
            ->orderBy('order')
            ->get(['id', 'title', 'href', 'icon', 'is_external', 'order']);

        return response()->json([
            'main'   => $mainItems,
            'footer' => $footerItems,
        ]);
    }
}
