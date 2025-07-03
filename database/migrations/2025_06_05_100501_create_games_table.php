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
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tournament_id')
                ->constrained('tournaments')
                ->onDelete('cascade');
            $table->integer('match_number')->nullable(); // Unique match number within the tournament
            $table->foreignId('team1_id')
                ->constrained('teams')
                ->onDelete('cascade');
            $table->foreignId('team2_id')
                ->constrained('teams')
                ->onDelete('cascade');
            $table->dateTime('scheduled_at')->nullable();
            $table->dateTime('played_at')->nullable();
            $table->enum('status', ['scheduled', 'ongoing', 'completed', 'cancelled'])->default('scheduled');
            $table->integer('team1_score')->default(0);
            $table->integer('team2_score')->default(0);
            $table->string('map')->nullable();
            $table->string('result')->nullable(); // e.g., "2-0", "1-2", etc.
            $table->integer('duration')->nullable(); // Duration in seconds
            $table->integer('matchup_count')->default(0); // Current game number, useful when multiple games played in a matchup
            $table->text('notes')->nullable(); // Additional notes about the game
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('games');
    }
};
