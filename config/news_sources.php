<?php

return [
    'sources' => [
        'newsapi' => [
            'base_url' => env('NEWS_API_URL'),
            'params' => [
                'country' => 'us',
                'pageSize' => 10,
                'apiKey' => env('NEWS_API_KEY'),
            ]
        ],
        'newyorktimes' => [
            'base_url' => env('NEW_YORK_TIMES_API_URL'),
            'params' => [
                'api-key' => env('NEW_YORK_TIMES_API_KEY'),
            ]
        ],
        'theguardian' => [
            'base_url' => env('GUARDIAN_API_URL'),
            'params' => [
                'api-key' => env('GUARDIAN_API_KEY'),
            ]
        ],

    ],
];
