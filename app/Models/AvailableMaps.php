<?php

namespace App\Models;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Vite;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

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
            $asset = "https://raw.githubusercontent.com/ghostcap-gaming/cs2-map-images/refs/heads/main/cs2/{$this->map_code}.png";
            if(!file_exists($asset))
            {
                $asset = Vite::asset("resources/images/maps.jpg"); 
            }
            return $asset;
        }

        // return asset("images/maps_thumbnails/{$this->image_name}");
        return Storage::temporaryUrl("maps_thumbnails/{$this->image_name}", now()->addMinutes(5), [
            'ResponseContentDisposition' => 'inline; filename="' . $this->image_name . '"',
        ]);
    }
}
