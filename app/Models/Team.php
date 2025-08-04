<?php

namespace App\Models;

use Illuminate\Support\Facades\Vite;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Team extends Model
{

    protected $fillable = [
        'name',
        'tag',
        'flag',
        'captain_id',
        'logo_extension',
    ];

    public function players(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function tournaments(): BelongsToMany
    {
        return $this->belongsToMany(Tournament::class);
    }

    // public function games(): HasMany
    // {
    //     return $this->hasMany(Game::class);
    // }

    public function captain(): BelongsTo
    {
        return $this->belongsTo(User::class, 'captain_id');
    }

    public function logoUrl(): string
    {
        $file = Storage::get("team_logos/{$this->id}.{$this->logo_extension}");

        if (!$file) {
            return Vite::asset('resources/images/default_avatar.png'); // Fallback to a default logo if not found
        }

        return Storage::temporaryUrl("team_logos/{$this->id}.{$this->logo_extension}", now()->addMinutes(5));
    }
}
