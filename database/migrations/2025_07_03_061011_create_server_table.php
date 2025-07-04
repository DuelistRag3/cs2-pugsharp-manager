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
            $table->foreignId('game_id')
                ->nullable()
                ->default(null) // Nullable foreign key to the game table
                ->constrained('games')
                ->onDelete('set null'); // Foreign key to the game table
            $table->string('rcon_password')->nullable(); // RCON password for the server
            $table->timestamps();
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
