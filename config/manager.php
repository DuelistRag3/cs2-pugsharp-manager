<?php

return [

    'steam_api_key' => env("STEAM_API_KEY", ""),

    'api_bearer_token' => env("API_BEARER_TOKEN", "1"),

    'round_names' => (function () {
        $allRounds = [
            'Zweiunddreißigstel-Finale',
            'Sechzehntelfinale',
            'Achtelfinale',
            'Viertelfinale',
            'Halbfinale',
            'Finale',
        ];
        $result = [];
        for ($i = 1; $i <= count($allRounds); $i++) {
            $result[$i] = array_slice($allRounds, -$i);
        }
        return $result;
    })(),
];
