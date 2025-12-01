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
        $asset = Vite::asset("resources/images/maps.jpg");

        if($this->image_name) {
            $asset = Storage::temporaryUrl("maps_thumbnails/{$this->image_name}", now()->addMinutes(5), [
                'ResponseContentDisposition' => 'inline; filename="' . $this->image_name . '"',
            ]);
        }

        if($this->map_code) {
            $url = "https://raw.githubusercontent.com/ghostcap-gaming/cs2-map-images/refs/heads/main/cs2/{$this->map_code}.png";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL,$url);
            // don't download content
            curl_setopt($ch, CURLOPT_NOBODY, 1);
            curl_setopt($ch, CURLOPT_FAILONERROR, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

            $result = curl_exec($ch);
            if($result !== FALSE)
            {
                $asset = $url;
            }
        }

        return $asset;
    }
}
