<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiLogging extends Model
{
    protected $fillable = [
        'type',
        'payload',
    ];

    protected $table = 'api_logging';
}
