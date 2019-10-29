<?php

use Osm\Data\Formulas\Types;

return [
    'distinct_count' => [
        'args' => [
            ['data_type' => Types::ANY],
        ],
        'return_data_type' => Types::INT_,
    ],
    'count' => [
        'args' => [
            ['data_type' => Types::ANY],
        ],
        'return_data_type' => Types::INT_,
    ],
    'sha1' => [
        'args' => [
            ['data_type' => Types::STRING_],
        ],
        'return_data_type' => Types::STRING_,
    ],
];