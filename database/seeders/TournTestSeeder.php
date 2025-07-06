<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Server;
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
            'match_rounds' => 6, // Number of rounds per match, default is 24 for CS2
            'overtime_rounds' => 2, // Number of overtime rounds, default is
            'status' => 'ongoing', // e.g., scheduled, ongoing, completed
            'team_size' => 1, // 1 for testing purposes
            'max_teams' => 2,
        ]);

        $team1 = $tourn->teams()->create([
            'name' => 'Team Lara',
            'tag' => 'TN',
            'flag' => 'DE',
        ]);

        $team2 = $tourn->teams()->create([
            'name' => 'Team Chris',
            'tag' => 'TC',
            'flag' => 'DE',
        ]);

        $team1->players()->create([
            'steam_id' => '76561198105304560',
            'steam_name' => 'Milkaa',
            'steam_avatar' => 'https://avatars.steamstatic.com/8df3fbb9717a9433d4c709138700c25228676cb9_full.jpg',
            'steam_url' => 'https://steamcommunity.com/id/betzog/',
        ]);

        $team2->players()->create([
            'steam_id' => '76561198348669227',
            'steam_name' => '[DTV] Duelist',
            'steam_avatar' => 'https://avatars.steamstatic.com/3519cf1ee9d0451211ee467dc776a02b53830c68_full.jpg',
            'steam_url' => 'https://steamcommunity.com/id/Duelist_DTV/',
        ]);

        $server = Server::create([
            'ip_address' => '127.0.0.1',
            'port' => 27015,
            'rcon_password' => 'test',
        ]);

        $tourn->generateMatchPlan();
    }
}
