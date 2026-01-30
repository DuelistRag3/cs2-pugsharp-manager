<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Server extends Model
{
    protected $fillable = [
        'ip_address',
        'port',
        'rcon_password',
    ];

    public function game(): HasOne
    {
        return $this->hasOne(Game::class, 'server_id', 'id');
    }

    public function block()
    {
        $this->status = 'occupied';
        $this->save();
    }

    public function free()
    {
        $this->status = 'free';
        $this->save();
    }
}
