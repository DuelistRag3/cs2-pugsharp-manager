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
        Schema::create('game_maps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_id')
                ->constrained('games')
                ->onDelete('cascade'); // Foreign key to the game table
            $table->integer('map_number')->unsigned(); // Map number in the matchup
            $table->string('map_name')->nullable(); // Map Name
            $table->integer('team1_score')->default(0); // Score for team 1
            $table->integer('team2_score')->default(0); // Score for team 2
            $table->enum('status', ['scheduled', 'ongoing', 'paused', 'completed', 'cancelled'])
                ->default('scheduled'); // Status of the matchup
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('matchups');
    }
};
