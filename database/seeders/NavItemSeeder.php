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
            'href'    => '/system',
            'icon'    => 'Shield',
            'section' => 'main',
            'order'   => 3,
        ]);
        $system->roles()->sync([$developer->id, $superAdmin->id]);

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
