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
            'matchup_rounds' => 0, // 0: BO1, 1: BO3, 2: BO5
            'final_rounds' => 0, // 0: BO1, 1: BO3, 2: BO5
            'status' => 'scheduled', // e.g., scheduled, ongoing, completed
            'team_size' => 1, // 1 for testing purposes
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

        $team1->players()->create([
            'steam_id' => '12345678901234569',
            'steam_name' => 'Player Alpha 1',
            'steam_avatar' => 'https://example.com/avatar.png',
            'steam_url' => 'https://example.com/profile/2',
        ]);

        $team2->players()->create([
            'steam_id' => '12345678901234569',
            'steam_name' => 'Player Beta 1',
            'steam_avatar' => 'https://example.com/avatar.png',
            'steam_url' => 'https://example.com/profile/2',
        ]);
    }
}
