<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    protected $fillable = [
        'match_number',
        'team1_id',
        'team2_id',
        'tournament_id',
        'score_team1',
        'score_team2',
        'status', // e.g., scheduled, completed, etc.
    ]; 

    public function tournament()
    {
        return $this->belongsTo(Tournament::class);
    }

    public function team1()
    {
        return $this->belongsTo(Team::class, 'team1_id');
    }

    public function team2()
    {
        return $this->belongsTo(Team::class, 'team2_id');
    }
}
