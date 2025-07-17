<?php

namespace App\Models;

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
        return Storage::exists('maps_thumbnails/' . $this->image_name) ? Storage::temporaryUrl('maps_thumbnails/' . $this->image_name, now()->addMinutes(2)) : asset('images/default-map-thumbnail.png');
    }
}
