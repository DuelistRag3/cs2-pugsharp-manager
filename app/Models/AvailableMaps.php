<?php

namespace App\Models;

use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;

class AvailableMaps extends Model
{
    protected $fillable = [
        'name',
        'map_code',
        'image_name',
    ];

    /**
     * Get the image URL for the map.
     */
    public function getImageUrlAttribute()
    {
        if (!$this->image_name) {
            return asset("https://raw.githubusercontent.com/ghostcap-gaming/cs2-map-images/refs/heads/main/cs2/{$this->map_code}.png");
        }

        return asset("images/maps_thumbnails/{$this->image_name}");
    }
}
