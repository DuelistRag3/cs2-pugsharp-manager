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
            $table->string('name'); // Name of the server
            $table->timestamps();
        });

        Schema::table('games', function (Blueprint $table) {
            $table->foreignId('servers_id')
                ->nullable()
                ->constrained('server')
                ->onDelete('set null'); // Foreign key to the server table
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
