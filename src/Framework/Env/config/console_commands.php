<?php

use Osm\Framework\Env\Commands;
use Symfony\Component\Console\Input\InputArgument;

return [
    'env' => [
        'description' => osm_t("Gets or sets environment variables"),
        'class' => Commands\Env::class,
        'arguments' => [
            'variable' => [
                'type' => InputArgument::IS_ARRAY,
                'description' => osm_t("'VAR' shows variable, 'VAR=' clears variable, VAR=value sets variable")
            ],
        ],
    ],
];