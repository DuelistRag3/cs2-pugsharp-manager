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
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('steam_id')->nullable()->after('email_verified_at');
            $table->string('steam_name')->nullable()->after('steam_id');
            $table->string('steam_avatar')->nullable()->after('steam_name');
            $table->string('steam_url')->nullable()->after('steam_avatar');
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
