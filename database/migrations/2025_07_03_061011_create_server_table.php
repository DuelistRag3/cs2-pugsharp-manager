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
        Schema::create('servers', function (Blueprint $table) {
            $table->id();
            $table->string('ip_address'); // IP address / Hostname for the server
            $table->integer('port')->unsigned(); // Port number for the server
            $table->string('rcon_password')->nullable(); // RCON password for the server
            $table->enum('status', ['free', 'occupied'])->default('free'); // Status of the server
            $table->timestamps();
        });

        Schema::table('games', function (Blueprint $table) {
            $table->foreignId('server_id')
                ->nullable()
                ->constrained('servers')
                ->onDelete('set null')
                ->after('matchup_count'); // Set to null if the server is deleted
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('server');
    }
};
