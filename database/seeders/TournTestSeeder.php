<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Tournament;
use Illuminate\Database\Seeder;

class TournTestSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $tourn = Tournament::create([
            'name' => 'Test Tournament',
            'description' => 'This is a test tournament for seeding purposes.',
            'start_date' => now()->addDays(7),
            'end_date' => now()->addDays(14),
            'matchup_rounds' => 1,
            'final_rounds' => 1,
            'status' => 'ongoing', // e.g., scheduled, ongoing, completed
            'max_teams' => 2,
        ]);

        $team1 = $tourn->teams()->create([
            'name' => 'Team Alpha',
            'tag' => 'ALPHA',
            'flag' => 'DE',
        ]);

        $team2 = $tourn->teams()->create([
            'name' => 'Team Beta',
            'tag' => 'BETA',
            'flag' => 'DE',
        ]);

        for ($i = 1; $i <= 5; $i++) {
            $team1->players()->create([
                'steam_id' => '12345678901234569',
                'steam_name' => 'Player Alpha ' . $i,
                'steam_avatar' => 'https://example.com/avatar' . $i . '.png',
                'steam_url' => 'https://example.com/profile' . $i,
            ]);
            $team2->players()->create([
                'steam_id' => '12345678901234569',
                'steam_name' => 'Player Beta ' . $i,
                'steam_avatar' => 'https://example.com/avatar' . ($i + 5) . '.png',
                'steam_url' => 'https://example.com/profile' . ($i + 5),
            ]);
        }
    }
}
