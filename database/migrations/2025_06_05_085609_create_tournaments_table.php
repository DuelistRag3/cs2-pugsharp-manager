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
            $table->dateTime('registration_deadline')->nullable()->comment('if null, registration ends with tournament start'); // if null, registration ends with tournament start
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->integer('team_size')->default(5)->comment('Default team size for CS2'); // Default team size for CS2
            $table->integer('max_teams')->default(0);
            $table->integer('matchup_rounds')->default(0)->comment('0: BO1, 1: BO3, 2: BO5'); // 0: BO1, 1: BO3, 2: BO5
            $table->integer('final_rounds')->default(0)->comment('0: BO1, 1: BO3, 2: BO5'); // 0: BO1, 1: BO3, 2: BO5
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
