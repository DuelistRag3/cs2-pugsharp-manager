<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    public function tournament()
    {
        return $this->belongsTo(Tournament::class);
    }
}
