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
        Schema::create('tournaments', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->text('description')->nullable();
            $table->integer('type')->comment('0: Bracket, 1: Round Robin')->nullable(); // 0: Bracket, 1: Round Robin
            $table->dateTime('registration_deadline')->nullable()->comment('if null, registration ends with tournament start'); // if null, registration ends with tournament start
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable()->default(null);
            $table->integer('team_size')->default(5)->comment('Default team size for CS2'); // Default team size for CS2
            $table->integer('max_teams')->default(2)->min; // Minimum 2 teams
            $table->integer('maps_each_game')->default(1);
            $table->integer('maps_final_game')->default(1);
            $table->integer('map_rounds')->default(24)->comment('Number of rounds per match, default is 24 for CS2'); // Number of rounds per map, default is 24 for CS2
            $table->integer('map_overtime_rounds')->default(6)->comment('Number of overtime rounds, default is 6 for CS2'); // Number of overtime rounds, default is 6 for CS2
            $table->json('maps')->nullable()->comment('List of maps available for the tournament'); // List of maps available for the tournament
            $table->enum('status', ['scheduled', 'ongoing', 'completed', 'cancelled'])->default('scheduled');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tournaments');
    }
};
