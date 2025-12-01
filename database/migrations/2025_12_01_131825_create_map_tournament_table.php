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
        Schema::create('map_tournament', function (Blueprint $table) {
            $table->id();
            $table->foreignId('map_id')->constrained('available_maps')->onDelete('cascade');
            $table->foreignId('tournament_id')->constrained('tournaments')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('map_tournament');
    }
};
