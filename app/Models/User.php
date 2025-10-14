<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Vite;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'steam_id',
        'steam_name',
        'steam_avatar',
        'steam_url',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function profilePicture(): string
    {
        return $this->steam_avatar ?? Vite::asset('resources/images/default_avatar.png');
    }

    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class);
    }

    public function isTeamCaptain(Team $team): bool
    {
        return $this->id === $team->captain_id;
    }

    public function teamInvitations(): HasMany
    {
        return $this->hasMany(TeamInvitation::class);
    }

    public function tournaments(): BelongsToMany
    {
        return $this->belongsToMany(TeamTournament::class, 'team_tournament_player');
    }

    public function getNotificationsCount(): int
    {
        $count = 0;

        // Count team invitations
        $count += $this->teamInvitations()->count();

        return $count;
    }

    // public function ongoingMatches()
    // {
    //     $matches = collect();
    //     foreach($this->teams as $team) {
    //         $matches = $matches->merge($team->games()->where('status', 'ongoing')->get());
    //     }
    //     return $matches->sortByDesc('created_at');
    // }
    
    public function matchHistory()
        {
            $pivotEntries = \DB::table('team_tournament_player')
                ->where('user_id', $this->id)
                ->get();

            $teamTournamentIds = $pivotEntries->pluck('team_tournament_id')->toArray();

            $teamTournaments = \App\Models\TeamTournament::whereIn('id', $teamTournamentIds)->get();

            $teamIds = $teamTournaments->pluck('team_id')->toArray();
            $tournamentIds = $teamTournaments->pluck('tournament_id')->toArray();

            $games = \App\Models\Game::where('status', 'completed')
                ->where(function ($query) use ($teamIds, $tournamentIds) {
                    $query->where(function ($q) use ($teamIds, $tournamentIds) {
                        $q->whereIn('team1_id', $teamIds)
                        ->whereIn('tournament_id', $tournamentIds);
                    })
                    ->orWhere(function ($q) use ($teamIds, $tournamentIds) {
                        $q->whereIn('team2_id', $teamIds)
                        ->whereIn('tournament_id', $tournamentIds);
                    });
                })
                ->orderByDesc('created_at')
                ->get();

            return $games;
        }

    public function map_scores(): HasMany
    {
        return $this->hasMany(GameMapPlayerScore::class, 'steam_id', 'steam_id');
    }

    public function stats(): array
    {
        $stats = [
            'kills' => 0,
            'headshots' => 0,
            'deaths' => 0,
            'assists' => 0,
        ];

        foreach ($this->map_scores as $score) {
            $stats['kills'] += $score->kills;
            $stats['headshots'] += $score->headshots;
            $stats['deaths'] += $score->deaths;
            $stats['assists'] += $score->assists;
        }

        return $stats;
    }

    public function isThisUser(): bool
    {
        return optional(auth()->user())->id === $this->id;
    }

    public function isAvailableForTournament(Tournament $tournament): bool
    {
        // Check if the user is already in the tournament
        foreach($this->tournaments as $userTournament) {
            if ($userTournament->tournament_id === $tournament->id) {
                return false;
            }
        }

        if(!$this->steam_id)
        {
            return false; // User must have a Steam ID to participate
        }

        return true;
    }
}
