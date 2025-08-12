<?php

use App\Models\Team;
use App\Models\Tournament;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('team_in_tournaments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')
                ->constrained('teams')
                ->onDelete('cascade');
            $table->foreignId('tournament_id')
                ->constrained('tournaments')
                ->onDelete('cascade');
            $table->timestamps();
        });

        $tournaments = Tournament::all();

        foreach($tournaments as $tournament) {
            $teams = Team::where('tournament_id', $tournament->id)->get();
            foreach($teams as $team) {
                DB::table('team_in_tournaments')->insert([
                    'team_id' => $team->id,
                    'tournament_id' => $tournament->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        Schema::table('teams', function (Blueprint $table) {
            $table->dropForeign(['tournament_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teams', function (Blueprint $table) {
            //
        });
    }
};
