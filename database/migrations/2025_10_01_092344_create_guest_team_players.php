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
        Schema::create('guest_team_players', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guest_team_id')->constrained('guest_teams')->onDelete('cascade');
            $table->string('steam_id');
            $table->string('steam_name');
            $table->string('steam_avatar');
            $table->string('steam_url');
            $table->string('kills')->default(0);
            $table->string('deaths')->default(0);
            $table->string('assists')->default(0);
            $table->string('flashbang_assists')->default(0);
            $table->string('teamkills')->default(0);
            $table->string('suicides')->default(0);
            $table->string('damage')->default(0);
            $table->string('util_damage')->default(0);
            $table->string('enemies_flashed')->default(0);
            $table->string('friendlies_flashed')->default(0);
            $table->string('knife_kills')->default(0);
            $table->string('headshot_kills')->default(0);
            $table->string('roundsplayed')->default(0);
            $table->string('bomb_plants')->default(0);
            $table->string('bomb_defuses')->default(0);
            $table->string('1kill_rounds')->default(0);
            $table->string('2kill_rounds')->default(0);
            $table->string('3kill_rounds')->default(0);
            $table->string('4kill_rounds')->default(0);  
            $table->string('5kill_rounds')->default(0);  
            $table->string('v1')->default(0);
            $table->string('v2')->default(0);
            $table->string('v3')->default(0);
            $table->string('v4')->default(0);
            $table->string('v5')->default(0);
            $table->string('firstkill_t')->default(0);
            $table->string('firstkill_ct')->default(0);
            $table->string('firstdeath_t')->default(0);
            $table->string('firstdeath_ct')->default(0);
            $table->string('tradekill')->default(0);
            $table->string('kast')->default(0);
            $table->string('contribution_score')->default(0);
            $table->string('mvp')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guest_team_players');
    }
};
