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
        Schema::create('rounds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_id')
                ->constrained('games')
                ->onDelete('cascade');
            $table->integer('round_number'); // Unique round number within the game
            $table->integer('team1_score')->default(0); // Score for Team 1 in this round
            $table->integer('team2_score')->default(0); // Score for Team 2 in this round
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rounds');
    }
};
