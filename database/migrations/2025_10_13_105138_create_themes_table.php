<?php

use App\Models\Theme;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('themes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('friendly_name');
            $table->string('author');
            $table->string('version');
            $table->timestamps();
        });

        $theme = new Theme();
        $theme->name = 'hltv';
        $theme->friendly_name = 'HLTV Style Theme';
        $theme->author = 'DuelistRage';
        $theme->version = '1.0';
        $theme->save();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('themes');
    }
};
