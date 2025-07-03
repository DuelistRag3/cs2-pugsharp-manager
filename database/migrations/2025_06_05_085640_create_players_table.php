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
        Schema::create('players', function (Blueprint $table) {
            $table->id();
            $table->integer('steam_id');
            $table->string('steam_name');
            $table->string('steam_avatar');
            $table->string('steam_url');
            $table->foreignId('team_id')
                ->constrained('teams')
                ->onDelete('cascade');
            $table->timestamps();

            // $table->unique('steam_id', 'team_id'); // Ensure unique player per team [REENABLE AFTER TESTING]
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('players');
    }
};
