<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GameMapPlayerScore extends Model
{
    protected $fillable = [
        'kills',
        'deaths',
        'assists',
        'flashbang_assists',
        'teamkills',
        'suicides',
        'damage',
        'util_damage',
        'enemies_flashed',
        'friendlies_flashed',
        'knife_kills',
        'headshot_kills',
        'roundsplayed',
        'bomb_plants',
        'bomb_defuses',
        '1kill_rounds',
        '2kill_rounds',
        '3kill_rounds',
        '4kill_rounds',
        '5kill_rounds',
        'clutches_won',
        'clutch_1v1',
        'clutch_1v2',
        'clutch_1v3',
        'clutch_1v4',
        'clutch_1v5',
        'first_kills_t',
        'first_kills_ct',
        'first_deaths_t',
        'first_deaths_ct',
        'tradekill',
        'kast',
        'contribution_score',
        'mvp'
    ];

    public function player()
    {
        return $this->belongsTo(User::class, 'steam_id', 'steam_id');
    }
}
