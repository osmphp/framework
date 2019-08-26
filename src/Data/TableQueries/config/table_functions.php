<?php

use Osm\Data\Formulas\Types;

return [
    'distinct_count' => [
        'args' => [
            ['data_type' => Types::ANY],
        ],
        'return_data_type' => Types::INT_,
    ],
];