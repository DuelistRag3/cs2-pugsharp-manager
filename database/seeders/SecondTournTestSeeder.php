<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Server;
use App\Models\Tournament;
use Illuminate\Database\Seeder;

class SecondTournTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
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
            'map_rounds' => 24, // Number of rounds per map, default is 24 for CS2
            'overtime_rounds' => 6, // Number of overtime rounds, default is 6
            'status' => 'ongoing', // e.g., scheduled, ongoing, completed
            'team_size' => 5, // 1 for testing purposes
            'max_teams' => 16,
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

        $server = Server::create([
            'ip_address' => '127.0.0.1',
            'port' => 27015,
            'rcon_password' => 'test',
        ]);

        $tourn->generateMatchPlan();
    }
}
