<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call([
            PermissionsSeeder::class,
        ]);

        $admin = User::create([
            'name' => env('ADMIN_NAME', 'Admin'),
            'email' => env('ADMIN_EMAIL', 'admin@local.de'),
            'email_verified_at' => now(),
            'password' => bcrypt(env('ADMIN_PASSWORD', 'admin')), // password
            'remember_token' => null,
        ]);

        $admin->assignRole('admin');
    }
}
