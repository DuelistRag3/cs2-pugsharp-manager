<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class InstallationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::create([
            'key' => 'app.name',
            'value' => 'CS2 Pugsharp Manager',
        ]);

        Setting::create([
            'key' => 'app.language',
            'value' => 'en',
        ]);
    }
}
