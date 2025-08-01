<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\PermissionRegistrar;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Create Roles
        $admin = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'admin']);
        $user = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'user']);
    }
}
