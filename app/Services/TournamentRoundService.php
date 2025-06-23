<?php

namespace App\Services;

class TournamentRoundService
{
    /**
     * Generate round names for a single elimination tournament.
     *
     * @param int $maxTeams Number of teams in the first round.
     * @return array<string>
     */
    public static function generateRounds(int $maxTeams): array
    {
        $rounds = [];
        $teams = $maxTeams;

        while ($teams >= 2) {
            $rounds[] = self::roundName($teams);
            $teams = intdiv($teams, 2);
        }

        return $rounds;
    }

    /**
     * Convert a team count to a human readable round name.
     */
    protected static function roundName(int $teams): string
    {
        return match ($teams) {
            2 => 'Finale',
            4 => 'Halbfinale',
            8 => 'Viertelfinale',
            16 => 'Achtelfinale',
            32 => 'Sechzehntelfinale',
            64 => 'Zweiunddreißigstelfinale',
            default => "Runde der {$teams}",
        };
    }

    /**
     * Build a tournament bracket using the registered teams and game results.
     *
     * @param \App\Models\Tournament $tournament
     * @param int|null $seed Optional seed to get deterministic pairings.
     * @return array<string, array<int, array{string, string}>>
     */
    public static function generateBracketForTournament(\App\Models\Tournament $tournament, ?int $seed = null): array
    {
        $teams = $tournament->teams()->pluck('name', 'id')->all();
        $teamIds = array_keys($teams);

        if ($seed !== null) {
            mt_srand($seed);
        }
        shuffle($teamIds);
        if ($seed !== null) {
            mt_srand();
        }

        $bracket = [];
        $currentTeamIds = $teamIds;

        while (count(array_filter($currentTeamIds)) >= 2) {
            $roundName = self::roundName(count(array_filter($currentTeamIds)));
            $matches = [];
            $nextRound = [];

            for ($i = 0; $i < count($currentTeamIds); $i += 2) {
                $team1Id = $currentTeamIds[$i] ?? null;
                $team2Id = $currentTeamIds[$i + 1] ?? null;

                $team1 = $team1Id ? ($teams[$team1Id] ?? 'TBD') : 'TBD';
                $team2 = $team2Id ? ($teams[$team2Id] ?? 'TBD') : 'TBD';

                $matches[] = [$team1, $team2];

                $winnerId = null;
                if ($team1Id && $team2Id) {
                    $game = $tournament->games()
                        ->whereIn('team1_id', [$team1Id, $team2Id])
                        ->whereIn('team2_id', [$team1Id, $team2Id])
                        ->first();

                    if ($game && $game->status === 'completed') {
                        $winnerId = $game->team1_score >= $game->team2_score ? $game->team1_id : $game->team2_id;
                    }
                }

                $nextRound[] = $winnerId;
            }

            $bracket[$roundName] = $matches;
            $currentTeamIds = $nextRound;
        }

        return $bracket;
    }

    /**
     * Generate bracket pairings for a single elimination tournament.
     * The bracket is returned as an associative array keyed by round name.
     *
     * @param array<int, string> $teams List of team names in the first round.
     * @return array<string, array<int, array{string, string}>>
     */
    public static function generateBracket(array $teams): array
    {
        $bracket = [];
        $currentTeams = $teams;

        while (count($currentTeams) >= 2) {
            $roundName = self::roundName(count($currentTeams));
            $matches = [];

            for ($i = 0; $i < count($currentTeams); $i += 2) {
                $team1 = $currentTeams[$i] ?? 'TBD';
                $team2 = $currentTeams[$i + 1] ?? 'TBD';
                $matches[] = [$team1, $team2];
            }

            $bracket[$roundName] = $matches;
            // Prepare next round placeholder team names
            $currentTeams = [];
            foreach ($matches as $index => $_match) {
                $currentTeams[] = "Winner of {$roundName} Match " . ($index + 1);
            }
        }

        return $bracket;
    }
}
