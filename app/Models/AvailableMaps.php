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
        $client = new Client();
        $response = $client->get("https://raw.githubusercontent.com/ghostcap-gaming/cs2-map-images/refs/heads/main/cs2/{$this->map_code}.png");

        if ($response->getStatusCode() === 200) {
            return asset("https://raw.githubusercontent.com/ghostcap-gaming/cs2-map-images/refs/heads/main/cs2/{$this->map_code}.png");
        }

        return asset('images/default-map-thumbnail.png');
    }
}
