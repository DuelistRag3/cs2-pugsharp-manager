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
        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('tag')->nullable();
            $table->string('flag')->nullable()->default("DE");
            $table->foreignId('tournament_id')
                ->constrained('tournaments')
                ->onDelete('cascade');
            $table->timestamps();
        });

        Schema::table('players', function (Blueprint $table) {
            $table->foreignId('team_id')
                ->nullable()
                ->constrained('teams')
                ->onDelete('set null')
                ->after('id');

            $table->unique(['steam_id', 'team_id']); // Ensure unique player per team
        });

        Schema::table('games', function (Blueprint $table) {
            $table->foreignId('team1_id')
                ->after('status')
                ->nullable()
                ->constrained('teams')
                ->onDelete('set null');
            $table->foreignId('team2_id')
                ->after('team1_id')
                ->nullable()
                ->constrained('teams')
                ->onDelete('set null');
            $table->foreignId('winner_team_id')
                ->after('team2_id')
                ->nullable()
                ->constrained(table: 'teams', indexName: 'id')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teams');
    }
};
