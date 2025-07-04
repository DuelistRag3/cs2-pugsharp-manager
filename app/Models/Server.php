<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Server extends Model
{
    protected $fillable = [
        'ip_address',
        'port',
        'rcon_password',
    ];

    /**
     * Get the games associated with the server.
     */
    public function game() : BelongsTo
    {
        return $this->belongsTo(Game::class, 'servers_id');
    }
}
