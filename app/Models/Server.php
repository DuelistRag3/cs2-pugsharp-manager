<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Server extends Model
{
    protected $fillable = [
        'name',
        'ip_address',
        'port',
    ];

    /**
     * Get the games associated with the server.
     */
    public function games() : HasMany
    {
        return $this->hasMany(Game::class, 'servers_id');
    }
}
