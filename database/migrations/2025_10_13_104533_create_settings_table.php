<?php

use App\Models\Setting;
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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key');
            $table->string('value')->nullable();
            $table->json('json_value')->nullable();
            $table->timestamps();
        });

        $setting = new Setting();
        $setting->key = 'theme';
        $setting->value = 'hltv';
        $setting->save();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
