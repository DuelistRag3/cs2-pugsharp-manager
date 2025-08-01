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
        Schema::table('game_map_player_scores', function (Blueprint $table) {
            $table->foreignId('steam_id')
                ->after('game_map_id')
                ->nullable()
                ->constrained(table: 'users', indexName:'steam_id')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('game_map_player_scores', function (Blueprint $table) {
            //
        });
    }
};
