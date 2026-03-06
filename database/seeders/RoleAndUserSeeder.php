<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RoleAndUserSeeder extends Seeder
{
    public function run(): void
    {
        if (! app()->isLocal()) {
            $this->command->warn('RoleAndUserSeeder skipped: only runs in the local environment.');
            return;
        }

        $roles = [
            ['name' => 'developer', 'label' => 'Developer'],
            ['name' => 'superAdmin', 'label' => 'Super Admin'],
            ['name' => 'admin', 'label' => 'Admin'],
            ['name' => 'customer', 'label' => 'Customer'],
        ];
        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role['name']], $role);
        }

        // Developer
        $dev = User::firstOrCreate([
            'email' => 'dev@ruventra.com',
        ], [
            'name' => 'Developer',
            'password' => Hash::make('devpassword'),
        ]);
        $dev->roles()->sync([Role::where('name', 'developer')->first()->id]);

        // Super Admin
        $super = User::firstOrCreate([
            'email' => 'superadmin@ruventra.com',
        ], [
            'name' => 'Super Admin',
            'password' => Hash::make('superpassword'),
        ]);
        $super->roles()->sync([Role::where('name', 'superAdmin')->first()->id]);

        // User dengan password 12345678
        $user = User::firstOrCreate([
            'email' => 'user@ruventra.com',
        ], [
            'name' => 'User',
            'password' => Hash::make('12345678'),
        ]);
        $user->roles()->sync([Role::where('name', 'customer')->first()->id]);
    }
}
