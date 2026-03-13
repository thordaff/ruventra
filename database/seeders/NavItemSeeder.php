<?php

namespace Database\Seeders;

use App\Models\NavItem;
use App\Models\Role;
use Illuminate\Database\Seeder;

class NavItemSeeder extends Seeder
{
    public function run(): void
    {
        // Hapus data lama
        NavItem::query()->delete();

        $developer  = Role::where('name', 'developer')->first();
        $superAdmin = Role::where('name', 'superAdmin')->first();
        $admin      = Role::where('name', 'admin')->first();

        // -------------------------------------------------------
        // MAIN NAV — Dashboard (parent)
        // -------------------------------------------------------
        $dashboard = NavItem::create([
            'title'   => 'Dashboard',
            'href'    => '/dashboard',
            'icon'    => 'LayoutGrid',
            'section' => 'main',
            'order'   => 1,
        ]);
        $dashboard->roles()->sync([$developer->id, $superAdmin->id, $admin->id]);

        $statistics = NavItem::create([
            'title'     => 'Statistics',
            'href'      => '/dashboard/statistics',
            'icon'      => 'BarChart2',
            'section'   => 'main',
            'parent_id' => $dashboard->id,
            'order'     => 1,
        ]);
        $statistics->roles()->sync([$developer->id, $superAdmin->id, $admin->id]);

        $revenue = NavItem::create([
            'title'     => 'Revenue',
            'href'      => '/dashboard/revenue',
            'icon'      => 'DollarSign',
            'section'   => 'main',
            'parent_id' => $dashboard->id,
            'order'     => 2,
        ]);
        $revenue->roles()->sync([$developer->id, $superAdmin->id]);

        // -------------------------------------------------------
        // MAIN NAV — Users (superAdmin & admin only)
        // -------------------------------------------------------
        $users = NavItem::create([
            'title'   => 'Users',
            'href'    => '/users',
            'icon'    => 'Users',
            'section' => 'main',
            'order'   => 2,
        ]);
        $users->roles()->sync([$superAdmin->id, $admin->id]);

        // -------------------------------------------------------
        // MAIN NAV — System (developer & superAdmin only)
        // -------------------------------------------------------
        $system = NavItem::create([
            'title'   => 'System',
            'href'    => '#',
            'icon'    => 'Shield',
            'section' => 'main',
            'order'   => 3,
        ]);
        $system->roles()->sync([$developer->id, $superAdmin->id]);

        $systemLogs = NavItem::create([
            'title'     => 'System Logs',
            'href'      => '/system/logs',
            'icon'      => 'FileText',
            'section'   => 'main',
            'parent_id' => $system->id,
            'order'     => 1,
        ]);
        $systemLogs->roles()->sync([$developer->id, $superAdmin->id]);

        // -------------------------------------------------------
        // MAIN NAV — SuperAdmin & Developer Only
        // -------------------------------------------------------
        $eventManagement = NavItem::create([
            'title'   => 'Event Management',
            'href'    => '/events/management',
            'icon'    => 'CalendarCheck',
            'section' => 'main',
            'order'   => 4,
        ]);
        $eventManagement->roles()->sync([$superAdmin->id, $developer->id]);

        // Parent baru untuk tools superAdmin & developer
        $toolsParent = NavItem::create([
            'title'   => 'Developer Tools',
            'href'    => '#',
            'icon'    => 'Settings',
            'section' => 'main',
            'order'   => 5,
        ]);
        $toolsParent->roles()->sync([$developer->id]);

        $userManagement = NavItem::create([
            'title'     => 'User Management',
            'href'      => '/users/management',
            'icon'      => 'UserCog',
            'section'   => 'main',
            'parent_id' => $toolsParent->id,
            'order'     => 1,
        ]);
        $userManagement->roles()->sync([$developer->id]);

        $reports = NavItem::create([
            'title'     => 'Reports & Analytics',
            'href'      => '/reports',
            'icon'      => 'BarChart',
            'section'   => 'main',
            'parent_id' => $toolsParent->id,
            'order'     => 2,
        ]);
        $reports->roles()->sync([$developer->id]);

        $apiPlayground = NavItem::create([
            'title'     => 'API Playground',
            'href'      => '/api/playground',
            'icon'      => 'TerminalSquare',
            'section'   => 'main',
            'parent_id' => $toolsParent->id,
            'order'     => 4,
        ]);
        $apiPlayground->roles()->sync([$developer->id]);

        $featureFlags = NavItem::create([
            'title'     => 'Feature Flags',
            'href'      => '/feature-flags',
            'icon'      => 'ToggleLeft',
            'section'   => 'main',
            'parent_id' => $toolsParent->id,
            'order'     => 5,
        ]);
        $featureFlags->roles()->sync([$developer->id]);

        $dbTools = NavItem::create([
            'title'     => 'Database Tools',
            'href'      => '/database/tools',
            'icon'      => 'Database',
            'section'   => 'main',
            'parent_id' => $toolsParent->id,
            'order'     => 6,
        ]);
        $dbTools->roles()->sync([$developer->id]);

        // -------------------------------------------------------
        // FOOTER NAV — external links (no role filter)
        // -------------------------------------------------------
        NavItem::create([
            'title'       => 'Repository',
            'href'        => 'https://github.com/laravel/vue-starter-kit',
            'icon'        => 'FolderGit2',
            'section'     => 'footer',
            'is_external' => true,
            'order'       => 1,
        ]);

        NavItem::create([
            'title'       => 'Documentation',
            'href'        => 'https://laravel.com/docs/starter-kits#vue',
            'icon'        => 'BookOpen',
            'section'     => 'footer',
            'is_external' => true,
            'order'       => 2,
        ]);
    }
}
