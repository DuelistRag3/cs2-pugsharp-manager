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
        });

        Schema::table('games', function (Blueprint $table) {
            $table->foreignId('team1_id')
                ->nullable()
                ->constrained('teams')
                ->onDelete('set null')
                ->after('status');
            $table->foreignId('team2_id')
                ->nullable()
                ->constrained('teams')
                ->onDelete('set null')
                ->after('team1_id');
            $table->foreignId('winner_team_id')
                ->nullable()
                ->constrained(table: 'teams', indexName: 'id')
                ->onDelete('set null')
                ->after('team2_id');
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
