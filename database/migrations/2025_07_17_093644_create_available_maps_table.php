<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('available_maps', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('map_code')->unique()->comment('Unique code for the map, used in configurations');
            $table->string('image_name')->nullable()->comment('Image name for the map, used for display purposes');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('available_maps');
    }
};
