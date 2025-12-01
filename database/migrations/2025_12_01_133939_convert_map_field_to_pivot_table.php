<?php

use Illuminate\Support\Facades\DB;
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
        $oldTournamentsMaps = DB::table('tournaments')->select('id', 'maps')->get();

        foreach($oldTournamentsMaps as $tournament) {
            $maps = json_decode($tournament->maps, true);
            if(is_array($maps)) {
                foreach($maps as $mapCode) {
                    $map = DB::table('available_maps')->where('map_code', $mapCode)->first();
                    if($map) {
                        DB::table('map_tournament')->insert([
                            'map_id' => $map->id,
                            'tournament_id' => $tournament->id,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            }
        }

        Schema::table('tournaments', function (Blueprint $table) {
            $table->dropColumn('maps');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $newTournamentsMaps = DB::table('map_tournament')
            ->select('tournament_id', DB::raw('GROUP_CONCAT(available_maps.map_code) as map_codes'))
            ->join('available_maps', 'map_tournament.map_id', '=', 'available_maps.id')
            ->groupBy('tournament_id')
            ->get();

        foreach($newTournamentsMaps as $tournament) {
            $mapCodesArray = explode(',', $tournament->map_codes);
            DB::table('tournaments')->where('id', $tournament->tournament_id)->update([
                'maps' => json_encode($mapCodesArray),
            ]);
        }
    }
};
