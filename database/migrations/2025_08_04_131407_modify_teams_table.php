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
        Schema::table('teams', function (Blueprint $table) {
            // Add a foreign key to the users table
            $table->foreignId('captain_id')
                ->after('tag')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->string('logo_extension')
                ->after('flag')
                ->nullable();
            $table->dropColumn('tournament_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
