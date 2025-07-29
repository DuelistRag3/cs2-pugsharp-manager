<?php

return [

    'steam_api_key' => env("STEAM_API_KEY", ""),

    'api_bearer_token' => env("API_BEARER_TOKEN", "1"),

    'round_name_tokens' => (function () {
        $allRounds = [
            'best_of_64',
            'best_of_32',
            'best_of_16',
            'quarter_final',
            'semi_final',
            'final',
        ];
        $result = [];
        for ($i = 1; $i <= count($allRounds); $i++) {
            $result[$i] = array_slice($allRounds, -$i);
        }
        return $result;
    })(),
];
