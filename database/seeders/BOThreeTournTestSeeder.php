<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Server;
use App\Models\Tournament;
use Illuminate\Database\Seeder;

class BOThreeTournTestSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $tourn = Tournament::create([
            'name' => ' Test Tournament BO3',
            'description' => 'This is a test tournament for seeding purposes.',
            'start_date' => now()->addDays(7),
            'team_size' => 5, // 0: BO1, 1: BO3, 2: BO5
            'max_teams' => 16, // 0: BO1, 1: BO3, 2: BO5
            'maps_each_game' => 1, // Best of
            'maps_final_game' => 1, // Best of
            'map_rounds' => 24, // Number of rounds per match, default is 24 for CS2
            'map_overtime_rounds' => 6, // Number of overtime rounds, default
            'status' => 'ongoing', // e.g., scheduled, ongoing, completed
        ]);

        // Create 16 teams with random names and tags
        for ($i = 1; $i <= 16; $i++) {
            $team = $tourn->teams()->create([
                'name' => 'Team ' . $i,
                'tag' => 'T' . $i,
                'flag' => 'DE', // Assuming all teams are from Germany for simplicity
            ]);
        };

        // Create players for each team
        foreach ($tourn->teams as $team) {
            for ($j = 1; $j <= 5; $j++) { // Assuming each team has 5 players
                $team->players()->create([
                    'steam_id' => '765611981053045' . str_pad($j, 2, '0', STR_PAD_LEFT),
                    'steam_name' => 'Player ' . $j . ' of ' . $team->name,
                    'steam_avatar' => 'https://avatars.steamstatic.com/8df3fbb9717a9433d4c709138700c25228676cb9_full.jpg',
                    'steam_url' => 'https://steamcommunity.com/id/player' . $j . '_of_' . $team->name . '/',
                ]);
            }
        };

        $tourn->generateMatchPlan(0);
    }
}
